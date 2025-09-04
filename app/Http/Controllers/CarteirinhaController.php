<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CarteirinhaController extends Controller
{
    /**
     * Exibe a carteirinha virtual do associado
     *
     * @param string $matricula
     * @return \Illuminate\View\View
     */
    public function show($matricula)
    {
        // Busca o usuário pela matrícula
        $user = User::where('matricula', $matricula)->first();
        
        if (!$user) {
            return view('carteirinha.invalida', ['matricula' => $matricula]);
        }
        
        // Verifica se o associado está aprovado
        if ($user->status !== 'aprovado') {
            return view('carteirinha.invalida', ['matricula' => $matricula, 'motivo' => 'nao_aprovado']);
        }
        
        // Gera um token único para a carteirinha (baseado na matrícula e timestamp)
        $token = $this->gerarTokenCarteirinha($user);
        
        // Gera QR Code com a URL da carteirinha
        $qrCodeUrl = route('carteirinha.show', $user->matricula);
        $qrCode = QrCode::size(200)->generate($qrCodeUrl);
        
        // Gera código de barras com a matrícula
        $barcode = $this->gerarCodigoBarras($user->matricula);
        
        return view('carteirinha.show', compact('user', 'token', 'qrCode', 'barcode'));
    }
    
    /**
     * Exibe a carteirinha para impressão
     *
     * @param string $matricula
     * @return \Illuminate\View\View
     */
    public function print($matricula)
    {
        // Busca o usuário pela matrícula
        $user = User::where('matricula', $matricula)->first();
        
        if (!$user) {
            return view('carteirinha.invalida', ['matricula' => $matricula]);
        }
        
        // Verifica se o associado está aprovado
        if ($user->status !== 'aprovado') {
            return view('carteirinha.invalida', ['matricula' => $matricula, 'motivo' => 'nao_aprovado']);
        }
        
        // Gera um token único para a carteirinha
        $token = $this->gerarTokenCarteirinha($user);
        
        // Gera QR Code com a URL da carteirinha
        $qrCodeUrl = route('carteirinha.show', $user->matricula);
        $qrCode = QrCode::size(200)->generate($qrCodeUrl);
        
        // Gera código de barras com a matrícula
        $barcode = $this->gerarCodigoBarras($user->matricula);
        
        return view('carteirinha.print', compact('user', 'token', 'qrCode', 'barcode'));
    }
    
    /**
     * Gera um token único para a carteirinha
     *
     * @param User $user
     * @return string
     */
    private function gerarTokenCarteirinha($user)
    {
        // Cria um token único baseado na matrícula, ID e timestamp
        $dados = $user->matricula . '|' . $user->id . '|' . now()->timestamp;
        return base64_encode($dados);
    }
    
    /**
     * Gera código de barras Code128 para a matrícula
     *
     * @param string $matricula
     * @return string
     */
    private function gerarCodigoBarras($matricula)
    {
        // Retorna apenas a matrícula para ser usada no CSS
        return $matricula;
    }
    
    /**
     * Valida se o token da carteirinha é válido
     *
     * @param string $token
     * @return bool
     */
    public function validarToken($token)
    {
        try {
            $dados = base64_decode($token);
            $partes = explode('|', $dados);
            
            if (count($partes) !== 3) {
                return false;
            }
            
            $matricula = $partes[0];
            $userId = $partes[1];
            $timestamp = $partes[2];
            
            // Verifica se o token não expirou (24 horas)
            if (now()->timestamp - $timestamp > 86400) {
                return false;
            }
            
            // Verifica se o usuário existe e está aprovado
            $user = User::where('matricula', $matricula)
                       ->where('id', $userId)
                       ->where('status', 'aprovado')
                       ->first();
            
            return $user !== null;
            
        } catch (\Exception $e) {
            return false;
        }
    }
}
