<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Parceria;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ParceriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parcerias = Parceria::orderBy('ordem')->orderBy('nome_empresa')->get();
        return view('admin.parcerias.index', compact('parcerias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Parceria::getCategorias();
        $tiposDesconto = Parceria::getTiposDesconto();
        return view('admin.parcerias.create', compact('categorias', 'tiposDesconto'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome_empresa' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'endereco' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'categoria' => 'required|string|max:50',
            'tipo_desconto' => 'required|string|in:percentual,valor_fixo,desconto_especial',
            'valor_desconto' => 'required|numeric|min:0',
            'valor_minimo_pedido' => 'nullable|numeric|min:0',
            'condicoes_desconto' => 'nullable|string',
            'logo' => 'nullable|file|mimes:jpeg,jpg,png|max:2048',
            'ativo' => 'nullable|boolean',
            'destaque' => 'nullable|boolean',
            'ordem' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except(['logo']);
            
            // Tratar checkboxes
            $data['ativo'] = $request->has('ativo') ? true : false;
            $data['destaque'] = $request->has('destaque') ? true : false;
            
            // Upload da logo
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                
                // Validação adicional do MIME type
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!in_array($logo->getMimeType(), $allowedMimes)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tipo de arquivo não suportado. Use apenas imagens JPEG ou PNG.',
                        'errors' => [
                            'logo' => ['Tipo de arquivo não suportado. Use apenas imagens JPEG ou PNG.']
                        ]
                    ], 422);
                }
                
                $logoName = time() . '_' . $logo->getClientOriginalName();
                $logo->storeAs('public/logos', $logoName);
                $data['logo'] = $logoName;
            }
            
            $parceria = Parceria::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Parceria criada com sucesso!',
                'redirect' => route('admin.parcerias.index')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $parceria = Parceria::findOrFail($id);
        return view('admin.parcerias.show', compact('parceria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $parceria = Parceria::findOrFail($id);
        $categorias = Parceria::getCategorias();
        $tiposDesconto = Parceria::getTiposDesconto();
        return view('admin.parcerias.edit', compact('parceria', 'categorias', 'tiposDesconto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $parceria = Parceria::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nome_empresa' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'endereco' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'categoria' => 'required|string|max:50',
            'tipo_desconto' => 'required|string|in:percentual,valor_fixo,desconto_especial',
            'valor_desconto' => 'required|numeric|min:0',
            'valor_minimo_pedido' => 'nullable|numeric|min:0',
            'condicoes_desconto' => 'nullable|string',
            'logo' => 'nullable|file|mimes:jpeg,jpg,png|max:2048',
            'ativo' => 'boolean',
            'destaque' => 'boolean',
            'ordem' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except(['logo']);
            
            // Tratar checkboxes
            $data['ativo'] = $request->has('ativo') ? true : false;
            $data['destaque'] = $request->has('destaque') ? true : false;
            
            // Upload da nova logo
            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                
                // Validação adicional do MIME type
                $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!in_array($logo->getMimeType(), $allowedMimes)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tipo de arquivo não suportado. Use apenas imagens JPEG ou PNG.',
                        'errors' => [
                            'logo' => ['Tipo de arquivo não suportado. Use apenas imagens JPEG ou PNG.']
                        ]
                    ], 422);
                }
                
                // Remove a logo antiga se existir
                if ($parceria->logo) {
                    Storage::delete('public/logos/' . $parceria->logo);
                }
                
                $logoName = time() . '_' . $logo->getClientOriginalName();
                $logo->storeAs('public/logos', $logoName);
                $data['logo'] = $logoName;
            }
            
            $parceria->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Parceria atualizada com sucesso!',
                'redirect' => route('admin.parcerias.index')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $parceria = Parceria::findOrFail($id);
            
            // Remove a logo se existir
            if ($parceria->logo) {
                Storage::delete('public/logos/' . $parceria->logo);
            }
            
            $parceria->delete();

            return response()->json([
                'success' => true,
                'message' => 'Parceria excluída com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle status ativo/inativo
     */
    public function toggleStatus($id)
    {
        try {
            $parceria = Parceria::findOrFail($id);
            $parceria->ativo = !$parceria->ativo;
            $parceria->save();

            return response()->json([
                'success' => true,
                'message' => $parceria->ativo ? 'Parceria ativada!' : 'Parceria desativada!',
                'ativo' => $parceria->ativo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle destaque
     */
    public function toggleDestaque($id)
    {
        try {
            $parceria = Parceria::findOrFail($id);
            $parceria->destaque = !$parceria->destaque;
            $parceria->save();

            return response()->json([
                'success' => true,
                'message' => $parceria->destaque ? 'Parceria em destaque!' : 'Destaque removido!',
                'destaque' => $parceria->destaque
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }
}
