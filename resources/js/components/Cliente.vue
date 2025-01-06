<template>
    <div class="container mx-auto p-4">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Gestión de Clientes</h2>
        <button @click="showModalCreate" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
          <i class="fas fa-plus mr-2"></i> Nuevo Cliente
        </button>
      </div>
  
      <!-- Tabla de clientes -->
      <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
          <div class="flex justify-between items-center">
            <input 
              v-model="searchTerm"
              type="text"
              placeholder="Buscar clientes..."
              class="p-2 border rounded w-1/3"
            />
          </div>
        </div>
  
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DNI/RUC</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apellido Paterno</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Apellido Materno</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="cliente in clientesFiltrados" :key="cliente.ID">
                <td class="px-6 py-4 whitespace-nowrap">{{ cliente.DNI || 'Cliente General' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ cliente.Nombre || '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ cliente.Apellido_Pat || '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ cliente.Apellido_Mat || '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button 
                    @click="showModalEdit(cliente)" 
                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                  >
                    <i class="fas fa-edit"></i>
                  </button>
                  <button 
                    @click="confirmarEliminar(cliente)" 
                    class="text-red-600 hover:text-red-900"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  
      <!-- Modal de creación/edición -->
      <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">{{ editMode ? 'Editar Cliente' : 'Nuevo Cliente' }}</h3>
            <button @click="closeModal" class="text-gray-600 hover:text-gray-900">
              <i class="fas fa-times"></i>
            </button>
          </div>
  
          <form @submit.prevent="submitForm">
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">DNI/RUC:</label>
              <input 
                v-model="formData.DNI" 
                type="text"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                :disabled="editMode"
              />
            </div>
  
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
              <input 
                v-model="formData.Nombre" 
                type="text"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              />
            </div>
  
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Apellido Paterno:</label>
              <input 
                v-model="formData.Apellido_Pat" 
                type="text"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              />
            </div>
  
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Apellido Materno:</label>
              <input 
                v-model="formData.Apellido_Mat" 
                type="text"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              />
            </div>
  
            <div class="flex justify-end">
              <button 
                type="button" 
                @click="closeModal"
                class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600"
              >
                Cancelar
              </button>
              <button 
                type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
              >
                {{ editMode ? 'Actualizar' : 'Crear' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'Cliente',
    data() {
      return {
        clientes: [],
        showModal: false,
        editMode: false,
        searchTerm: '',
        formData: {
          DNI: '',
          Nombre: '',
          Apellido_Pat: '',
          Apellido_Mat: ''
        },
        selectedCliente: null
      }
    },
  
    computed: {
      clientesFiltrados() {
        if (!this.searchTerm) return this.clientes;
        
        const termLower = this.searchTerm.toLowerCase();
        return this.clientes.filter(cliente => 
          (cliente.DNI && cliente.DNI.toLowerCase().includes(termLower)) ||
          (cliente.Nombre && cliente.Nombre.toLowerCase().includes(termLower)) ||
          (cliente.Apellido_Pat && cliente.Apellido_Pat.toLowerCase().includes(termLower)) ||
          (cliente.Apellido_Mat && cliente.Apellido_Mat.toLowerCase().includes(termLower))
        );
      }
    },
  
    methods: {
      async cargarClientes() {
        try {
          const response = await axios.get('/clientes');
          this.clientes = response.data;
        } catch (error) {
          console.error('Error al cargar clientes:', error);
          alert('Error al cargar los clientes');
        }
      },
  
      showModalCreate() {
        this.editMode = false;
        this.formData = {
          DNI: '',
          Nombre: '',
          Apellido_Pat: '',
          Apellido_Mat: ''
        };
        this.showModal = true;
      },
  
      showModalEdit(cliente) {
        this.editMode = true;
        this.selectedCliente = cliente;
        this.formData = { ...cliente };
        this.showModal = true;
      },
  
      closeModal() {
        this.showModal = false;
        this.formData = {
          DNI: '',
          Nombre: '',
          Apellido_Pat: '',
          Apellido_Mat: ''
        };
        this.selectedCliente = null;
      },
  
      async submitForm() {
        try {
          if (this.editMode) {
            await axios.put(`/clientes/${this.selectedCliente.ID}`, this.formData);
          } else {
            await axios.post('/clientes', this.formData);
          }
          
          await this.cargarClientes();
          this.closeModal();
          alert(this.editMode ? 'Cliente actualizado exitosamente' : 'Cliente creado exitosamente');
        } catch (error) {
          console.error('Error:', error);
          alert('Error al procesar el cliente');
        }
      },
  
      async confirmarEliminar(cliente) {
        if (confirm('¿Está seguro de eliminar este cliente?')) {
          try {
            await axios.delete(`/clientes/${cliente.ID}`);
            await this.cargarClientes();
            alert('Cliente eliminado exitosamente');
          } catch (error) {
            console.error('Error:', error);
            alert('Error al eliminar el cliente');
          }
        }
      }
    },
  
    mounted() {
      this.cargarClientes();
    }
  }
  </script>
  
  <style scoped>
  .loader {
    border: 4px solid #f3f3f3;
    border-radius: 50%;
    border-top: 4px solid #3498db;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  </style>