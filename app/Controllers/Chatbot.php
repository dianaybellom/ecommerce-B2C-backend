<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use OpenAI;
use OpenAI\Exceptions\ErrorException; // Importa la excepción específica de OpenAI

class Chatbot extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $mensaje = $this->request->getJSON(true)['mensaje'] ?? null;

        if (!$mensaje) {
            return $this->respond(['error' => 'Mensaje no proporcionado'], 400);
        }

        // Obtener productos del modelo
        $productoModel = new \App\Models\Producto();
        $productos = $productoModel->findAll();

        $productoTexto = "";
        foreach ($productos as $prod) {
            $productoTexto .= "- {$prod['nombre']}: {$prod['descripcion']}. Precio: \${$prod['precio']}\n";
        }

        // Llamar a OpenAI API con captura de excepción
        try {
            $apiKey = getenv('OPENAI_API_KEY');
            $client = OpenAI::client($apiKey);

            $response = $client->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => "Eres un asistente virtual de una tienda de ropa. Responde sobre productos usando esta lista:\n$productoTexto"],
                    ['role' => 'user', 'content' => $mensaje],
                ],
            ]);

            return $this->respond(['respuesta' => $response->choices[0]->message->content]);

        } catch (ErrorException $e) {
            // Captura errores específicos de OpenAI
            return $this->respond([
                'error' => 'Error de OpenAI',
                'mensaje' => $e->getMessage()
            ], 500);

        } catch (\Exception $e) {
            // Captura errores generales
            return $this->respond([
                'error' => 'Error interno del servidor',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }
}
