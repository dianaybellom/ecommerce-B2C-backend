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

    // Ruta de imagen por defecto
    private string $imagenPorDefecto = 'uploads/productos/image-not-found.png';

    private function esAdmin(): bool
    {
        $usuarioId = session()->get('usuario_id');
        if (!$usuarioId) return false;

        $usuario = (new Usuario())->find($usuarioId);
        return $usuario && $usuario['rol'] === 'ADMIN';
    }

    // Guarda imagen codificada en base64 y retorna el nombre del archivo
    private function guardarImagenBase64($base64, $nombre = null): ?string
    {
        if (empty($base64)) return null;

        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $coincidencia)) {
            $extension = strtolower($coincidencia[1]);
            $base64 = substr($base64, strpos($base64, ',') + 1);
        } else {
            return null;
        }

        if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
            return null;
        }

        $imagenData = base64_decode($base64);
        if ($imagenData === false) return null;

        $nombreArchivo = $nombre ?? uniqid('producto_', true) . '.' . $extension;
        $ruta = WRITEPATH . '../public/uploads/productos/' . $nombreArchivo;

        $directorio = dirname($ruta);
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        if (!file_put_contents($ruta, $imagenData)) {
            return null;
        }

        return $nombreArchivo;
    }

    // GET /producto
    public function index()
    {
        $productos = $this->model->findAll();

        foreach ($productos as &$producto) {
            if (empty($producto['imagen'])) {
                $producto['imagen'] = $this->imagenPorDefecto;
            }
        }

        return $this->respond($productos);
    }

    // GET /producto/{id}
    public function show($id = null)
    {
        $producto = $this->model->find($id);

        if (!$producto) {
            return $this->failNotFound('Producto no encontrado');
        }

        if (empty($producto['imagen'])) {
            $producto['imagen'] = $this->imagenPorDefecto;
        }

        return $this->respond($producto);
    }

    // POST /producto (individual o batch)
    public function create()
    {
        if (!$this->esAdmin()) {
            return $this->failUnauthorized('Solo los administradores pueden crear productos.');
        }

        $data = $this->request->getJSON(true);

        if (!$data) {
            return $this->failValidationErrors(['mensaje' => 'No se proporcionaron datos.']);
        }

        // Modo batch
        if (isset($data[0])) {
            foreach ($data as &$producto) {
                if (!empty($producto['imagen_base64'])) {
                    $archivo = $this->guardarImagenBase64($producto['imagen_base64']);
                    if ($archivo) {
                        $producto['imagen'] = 'uploads/productos/' . $archivo;
                    }
                    unset($producto['imagen_base64']);
                }
            }

            if (!$this->model->insertBatch($data)) {
                return $this->failServerError('No se pudieron insertar los productos.');
            }

            return $this->respondCreated(['mensaje' => 'Productos creados exitosamente (batch).']);
        }

        // Modo individual
        if (!empty($data['imagen_base64'])) {
            $archivo = $this->guardarImagenBase64($data['imagen_base64']);
            if ($archivo) {
                $data['imagen'] = 'uploads/productos/' . $archivo;
            }
            unset($data['imagen_base64']);
        }

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
            return $this->failValidationErrors(['mensaje' => 'No se recibieron datos para actualizar.']);
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

    // OPTIONS preflight handler para CORS
    public function optionsHandler()
    {
        return $this->response->setStatusCode(200);
    }
}