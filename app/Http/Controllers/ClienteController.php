<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ClienteController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            \Log::info('Iniciando registro de cliente', $request->all());

            // Determinar si es RUC por la longitud
            $esRuc = strlen($request->DNI) === 11;
            
            // Validación condicional
            if ($esRuc) {
                $request->validate([
                    'DNI' => 'required|string|max:20|unique:Cliente,DNI',
                    'Nombre' => 'required|string|max:255'
                ]);
            } else {
                $request->validate([
                    'DNI' => 'required|string|max:20|unique:Cliente,DNI',
                    'Nombre' => 'required|string|max:255',
                    'Apellido_Pat' => 'required|string|max:255',
                    'Apellido_Mat' => 'required|string|max:255'
                ]);
            }

            // Preparar datos del cliente
            $clienteData = [
                'DNI' => $request->DNI,
                'Nombre' => $request->Nombre,
                'Apellido_Pat' => $esRuc ? '' : $request->Apellido_Pat,
                'Apellido_Mat' => $esRuc ? '' : $request->Apellido_Mat
            ];

            $cliente = Cliente::create($clienteData);

            if (!$cliente) {
                throw new \Exception('No se pudo crear el cliente en la base de datos');
            }

            if (!$cliente->ID) {
                throw new \Exception('El cliente se creó pero no se generó un ID');
            }

            $clienteArray = [
                'ID' => $cliente->ID,
                'DNI' => $cliente->DNI,
                'Nombre' => $cliente->Nombre,
                'Apellido_Pat' => $cliente->Apellido_Pat,
                'Apellido_Mat' => $cliente->Apellido_Mat
            ];

            \Log::info('Cliente creado exitosamente', ['cliente' => $clienteArray]);

            DB::commit();

            return response()->json([
                'success' => true,
                'cliente' => $clienteArray,
                'message' => 'Cliente registrado exitosamente'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            \Log::error('Error de validación', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al registrar cliente', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    public function buscarPorDNI($documento)
    {
        try {
            // Determinar si es DNI o RUC por la longitud
            $tipoDocumento = strlen($documento) === 11 ? 'ruc' : 'dni';
            
            // Primero buscamos en nuestra base de datos
            $cliente = Cliente::where('DNI', $documento)->first();
    
            if (!$cliente) {
                // Si no está en base de datos, consultamos API
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . env('API_PERU_TOKEN'),
                    'Accept' => 'application/json',
                ])->get("https://apiperu.dev/api/{$tipoDocumento}/{$documento}");
    
                if ($response->successful() && $response->json()['success']) {
                    $data = $response->json()['data'];
                    
                    // Crear cliente automáticamente para ambos casos (DNI y RUC)
                    $clienteData = $tipoDocumento === 'dni' ? [
                        'DNI' => $documento,
                        'Nombre' => $data['nombres'],
                        'Apellido_Pat' => $data['apellido_paterno'],
                        'Apellido_Mat' => $data['apellido_materno']
                    ] : [
                        'DNI' => $documento,
                        'Nombre' => $data['nombre_o_razon_social'],
                        'Apellido_Pat' => '',
                        'Apellido_Mat' => ''
                    ];
    
                    // Crear el cliente en la base de datos
                    $cliente = Cliente::create($clienteData);
    
                    return response()->json([
                        'success' => true,
                        'cliente' => $cliente,
                        'message' => $tipoDocumento === 'dni' ? 
                            'Cliente DNI creado automáticamente' : 
                            'Cliente RUC creado automáticamente'
                    ]);
                }
    
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ], 404);
            }
    
            return response()->json([
                'success' => true,
                'cliente' => $cliente
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar cliente: ' . $e->getMessage()
            ], 500);
        }
    }

    public function obtenerClienteGeneral()
    {
        try {
            // Buscar cliente con DNI null
            $cliente = Cliente::whereNull('DNI')
                            ->whereNull('Nombre')
                            ->whereNull('Apellido_Pat')
                            ->whereNull('Apellido_Mat')
                            ->first();

            if (!$cliente) {
                // Si no existe, crear el cliente general
                $cliente = Cliente::create([
                    'DNI' => null,
                    'Nombre' => null,
                    'Apellido_Pat' => null,
                    'Apellido_Mat' => null
                ]);
            }

            return response()->json([
                'success' => true,
                'cliente' => $cliente
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener cliente general: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $clientes = Cliente::all();
            return response()->json($clientes);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener clientes: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            
            $cliente->update([
                'Nombre' => $request->Nombre,
                'Apellido_Pat' => $request->Apellido_Pat,
                'Apellido_Mat' => $request->Apellido_Mat
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Cliente actualizado exitosamente',
                'cliente' => $cliente
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar cliente: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function destroy($id)
    {
        try {
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Cliente eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar cliente: ' . $e->getMessage()
            ], 500);
        }
    }


}