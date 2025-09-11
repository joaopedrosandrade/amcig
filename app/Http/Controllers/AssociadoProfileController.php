<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssociadoProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe a página de perfil do associado
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        return view('associado.perfil', compact('user'));
    }

    /**
     * Atualiza a foto do usuário
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePhoto(Request $request)
    {
        // Debug: verificar o que está chegando
        \Log::info('Upload de foto iniciado', [
            'has_file' => $request->hasFile('photo'),
            'file_info' => $request->hasFile('photo') ? [
                'name' => $request->file('photo')->getClientOriginalName(),
                'size' => $request->file('photo')->getSize(),
                'mime' => $request->file('photo')->getMimeType(),
                'extension' => $request->file('photo')->getClientOriginalExtension(),
            ] : null,
            'all_input' => $request->all()
        ]);

        $validator = Validator::make($request->all(), [
            'photo' => 'required|file|mimes:jpeg,jpg,png,gif,webp|max:2048'
        ], [
            'photo.required' => 'Por favor, selecione uma foto.',
            'photo.file' => 'O arquivo deve ser válido.',
            'photo.mimes' => 'A imagem deve ser do tipo: jpeg, jpg, png, gif ou webp.',
            'photo.max' => 'A imagem não pode ser maior que 2MB.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $validator->errors()
            ], 422);
        }

        // Validação adicional do MIME type
        $file = $request->file('photo');
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            \Log::warning('MIME type não permitido', [
                'mime_type' => $file->getMimeType(),
                'filename' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => [
                    'photo' => ['Tipo de arquivo não suportado. Use apenas imagens JPEG, PNG, GIF ou WebP.']
                ]
            ], 422);
        }

        try {
            $user = Auth::user();
            
            // Deletar foto anterior se existir
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Fazer upload da nova foto
            $path = $request->file('photo')->store('photos', 'public');
            
            // Atualizar usuário
            $user->update(['photo' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Foto atualizada com sucesso!',
                'photo_url' => $user->photo_url
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar foto: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove a foto do usuário
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removePhoto()
    {
        try {
            $user = Auth::user();
            
            // Deletar foto se existir
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Atualizar usuário
            $user->update(['photo' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Foto removida com sucesso!',
                'photo_url' => $user->photo_url
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover foto: ' . $e->getMessage()
            ], 500);
        }
    }
}
