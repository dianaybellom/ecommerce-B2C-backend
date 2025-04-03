<?php

namespace App\Controllers;

use App\Models\Producto;
use App\Models\Usuario;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class ProductoController extends ResourceController
{
    use ResponseTrait;

    protected $modelName = Producto::class;
    protected $format    = 'json';

    private function esAdmin(): bool
    {
        $usuarioId = session()->get('usuario_id');
        if (!$usuarioId) return false;

        $usuario = (new Usuario())->find($usuarioId);
        return $usuario && $usuario['rol'] === 'ADMIN';
    }

    // GET /producto imagenes
    private function guardarImagenBase64($base64, $nombre = null): ?string
    {
        if (empty($base64)) return null;

        // Detectar tipo MIME
        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $coincidencia)) {
            $extension = strtolower($coincidencia[1]);
            $base64 = substr($base64, strpos($base64, ',') + 1);
        } else {
            return null; // formato inválido
        }

        // Validar extensión permitida
        if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
            return null;
        }

        // Decodificar base64
        $imagenData = base64_decode($base64);
        if ($imagenData === false) return null;

        // Generar nombre único
        $nombreArchivo = $nombre ?? uniqid('producto_', true) . '.' . $extension;

        // Ruta final
        $ruta = WRITEPATH . '../public/uploads/productos/' . $nombreArchivo;

        // Asegurar carpeta
        $directorio = dirname($ruta);
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        // Guardar imagen
        if (!file_put_contents($ruta, $imagenData)) {
            return null;
        }

        return $nombreArchivo;
    }

    // GET /producto
    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    // GET /producto/{id}
    public function show($id = null)
    {
        $producto = $this->model->find($id);
        return $producto
            ? $this->respond($producto)
            : $this->failNotFound('Producto no encontrado');
    }

    // POST /producto
    public function create()
    {
        if (!$this->esAdmin()) {
            return $this->failUnauthorized('Solo los administradores pueden crear productos.');
        }

        $data = $this->request->getJSON(true);
        if (!$data) {
            return $this->failValidationError('No se proporcionaron datos.');
        }

        // No se permite carga múltiple
        if (isset($data[0])) {
            return $this->failValidationError('No se permite carga múltiple con imagen_base64.');
        }

        // Procesar imagen base64
        if (!empty($data['imagen_base64'])) {
            $archivo = $this->guardarImagenBase64($data['imagen_base64']);
            if ($archivo) {
                $data['imagen'] = 'uploads/productos/' . $archivo;
            }
            unset($data['imagen_base64']);
        }

        // Insertar en la base de datos
        if (!$this->model->insert($data)) {
            return $this->failServerError('No se pudo insertar el producto.');
        }

        return $this->respondCreated(['mensaje' => 'Producto creado exitosamente']);
    }

    // PUT /producto/{id}
    public function update($id = null)
    {
        if (!$this->esAdmin()) {
            return $this->failUnauthorized('Solo los administradores pueden editar productos.');
        }

        $data = $this->request->getJSON(true);
        if (empty($data)) {
            return $this->failValidationError('No se recibieron datos para actualizar.');
        }

        if (!empty($data['imagen_base64'])) {
            $archivo = $this->guardarImagenBase64($data['imagen_base64']);
            if ($archivo) {
                $data['imagen'] = 'uploads/productos/' . $archivo;
            }
            unset($data['imagen_base64']);
        }

        if (!$this->model->update($id, $data)) {
            return $this->failServerError('No se pudo actualizar el producto.');
        }

        return $this->respond(['mensaje' => 'Producto actualizado correctamente']);
    }

    // DELETE /producto/{id}
    public function delete($id = null)
    {
        if (!$this->esAdmin()) {
            return $this->failUnauthorized('Solo los administradores pueden eliminar productos.');
        }

        if (!$this->model->delete($id)) {
            return $this->failServerError('No se pudo eliminar el producto.');
        }

        return $this->respondDeleted(['mensaje' => 'Producto eliminado']);
    }

    public function optionsHandler()
    {
        return $this->response->setStatusCode(200);
    }

}