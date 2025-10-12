<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Admin;
use App\AdminPermission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        // Permite acesso para superadmins ou admins com permissão
        $this->middleware(function ($request, $next) {
            $admin = auth('admin')->user();
            if (!$admin->isSuperAdmin() && !$admin->hasPermission('config_sistema', 'view')) {
                abort(403, 'Você não tem permissão para acessar este módulo.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Admin::with(['permissions', 'updatedBy']);

        // Filtro por busca
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $admins = $query->paginate(15)->appends($request->query());

        return view('admin.users.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menus = AdminPermission::getAvailableMenus();
        return view('admin.users.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAdminRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdminRequest $request)
    {
        DB::beginTransaction();
        
        try {
            // Criar o admin
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => $request->input('status', '1') === '1' ? 1 : 0,
                'is_superadmin' => $request->input('is_superadmin', '0') === '1' ? 1 : 0,
                'updated_by' => auth('admin')->id(),
            ]);

            // Sincronizar permissões se não for superadmin
            if (!$admin->is_superadmin && $request->has('permissions')) {
                $admin->syncPermissions($request->permissions, auth('admin')->id());
            }

            DB::commit();

            return redirect()
                ->route('admin.config.usuarios.index')
                ->with('success', 'Usuário administrativo criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar usuário administrativo: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        $admin->load(['permissions', 'updatedBy']);
        $permissions = $admin->getPermissionsByMenu();
        
        return view('admin.users.show', compact('admin', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        $menus = AdminPermission::getAvailableMenus();
        $permissions = $admin->getPermissionsByMenu();
        
        return view('admin.users.edit', compact('admin', 'menus', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAdminRequest  $request
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        DB::beginTransaction();
        
        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->input('status', '1') === '1' ? 1 : 0,
                'is_superadmin' => $request->input('is_superadmin', '0') === '1' ? 1 : 0,
                'updated_by' => auth('admin')->id(),
            ];

            // Atualizar senha apenas se fornecida
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $admin->update($updateData);

            // Sincronizar permissões se não for superadmin
            if (!$admin->is_superadmin && $request->has('permissions')) {
                $admin->syncPermissions($request->permissions, auth('admin')->id());
            } elseif ($admin->is_superadmin) {
                // Se virou superadmin, remover todas as permissões
                $admin->permissions()->delete();
            }

            DB::commit();

            return redirect()
                ->route('admin.config.usuarios.index')
                ->with('success', 'Usuário administrativo atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar usuário administrativo: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        // Não permitir que o admin se delete a si mesmo
        if ($admin->id === auth('admin')->id()) {
            return redirect()
                ->back()
                ->with('error', 'Você não pode excluir seu próprio usuário!');
        }

        // Não permitir excluir o último superadmin
        if ($admin->is_superadmin && Admin::where('is_superadmin', true)->count() <= 1) {
            return redirect()
                ->back()
                ->with('error', 'Não é possível excluir o último super administrador!');
        }

        DB::beginTransaction();
        
        try {
            // Deletar permissões primeiro (cascade)
            $admin->permissions()->delete();
            
            // Deletar o admin
            $admin->delete();

            DB::commit();

            return redirect()
                ->route('admin.config.usuarios.index')
                ->with('success', 'Usuário administrativo excluído com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir usuário administrativo: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status do admin (ativar/desativar)
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function toggleStatus(Admin $admin)
    {
        // Não permitir que o admin se desative a si mesmo
        if ($admin->id === auth('admin')->id()) {
            return redirect()
                ->back()
                ->with('error', 'Você não pode alterar seu próprio status!');
        }

        // Não permitir desativar o último superadmin
        if ($admin->is_superadmin && $admin->status && Admin::where('is_superadmin', true)->where('status', true)->count() <= 1) {
            return redirect()
                ->back()
                ->with('error', 'Não é possível desativar o último super administrador ativo!');
        }

        try {
            $admin->update([
                'status' => !$admin->status,
                'updated_by' => auth('admin')->id(),
            ]);

            $status = $admin->status ? 'ativado' : 'desativado';
            
            return redirect()
                ->back()
                ->with('success', "Usuário administrativo {$status} com sucesso!");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Erro ao alterar status do usuário: ' . $e->getMessage());
        }
    }
}
