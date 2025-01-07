<template>
  <div class="data-table-container">
    <h2 class="title">Gestión de Clientes</h2>

    <!-- Tabla de clientes -->
    <div class="table-container">
      <div class="search-container">
        <input 
          v-model="searchTerm"
          type="text"
          placeholder="Buscar clientes..."
          class="search-input"
        />
      </div>

      <table>
        <thead>
          <tr>
            <th>N°</th>
            <th>DNI/RUC</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(cliente, index) in clientesFiltrados" :key="cliente.ID">
            <td>{{ index + 1 }}</td>
            <td>{{ cliente.DNI || 'Cliente General' }}</td>
            <td>{{ cliente.Nombre || '-' }}</td>
            <td>{{ cliente.Apellido_Pat || '-' }}</td>
            <td>{{ cliente.Apellido_Mat || '-' }}</td>
            <td>
              <button @click="showModalEdit(cliente)" class="edit-button">
                <i class="fas fa-edit"></i>
              </button>
              <button @click="confirmarEliminar(cliente)" class="delete-button">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal de creación/edición -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal-content">
        <h3>{{ editMode ? 'Editar Cliente' : 'Nuevo Cliente' }}</h3>
        <div class="icon-container">
          <i class="fas fa-user-circle person-icon"></i>
        </div>

        <form @submit.prevent="submitForm">
          <div class="form-group">
            <label>DNI/RUC:</label>
            <input 
              v-model="formData.DNI" 
              type="text"
              :disabled="editMode"
            />
          </div>

          <div class="form-group">
            <label>Nombre:</label>
            <input 
              v-model="formData.Nombre" 
              type="text"
            />
          </div>

          <div class="form-group">
            <label>Apellido Paterno:</label>
            <input 
              v-model="formData.Apellido_Pat" 
              type="text"
            />
          </div>

          <div class="form-group">
            <label>Apellido Materno:</label>
            <input 
              v-model="formData.Apellido_Mat" 
              type="text"
            />
          </div>

          <div class="modal-footer">
            <button 
              type="button" 
              @click="closeModal"
              class="cancel-button"
            >
              Cancelar
            </button>
            <button 
              type="submit"
              class="submit-button"
            >
              {{ editMode ? 'Actualizar' : 'Crear' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Botón para agregar cliente -->
    <button @click="showModalCreate" class="add-button">
      <i class="fas fa-plus"></i> Agregar Cliente
    </button>
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
/* Estilos para el título */
.title {
  font-size: 2em;
  font-weight: bold;
  text-align: center;
}

/* Estilos para el contenedor principal */
.data-table-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Estilos para la búsqueda */
.search-container {
  margin-bottom: 10px;
}

.search-input {
  width: 100%;
  max-width: 300px;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

/* Estilos para la tabla */
.table-container {
  margin-top: 30px;
  width: 100%;
}

table {
  width: 100%;
  border: 1px solid #ddd;
  text-align: left;
  border-collapse: collapse;
}

th, td {
  padding: 8px 10px;  /* Reducido de 12px 15px */
  border-bottom: 1px solid #ddd;
  font-size: 14px;    /* Añadido para reducir el tamaño del texto */
}

th {
  background-color: #333;
  color: #fff;
}

/* Estilos para los botones de acción */
.edit-button, .delete-button {
  border: none;
  background-color: transparent;
  cursor: pointer;
  font-size: 18px;
  margin-right: 10px;
}

.edit-button {
  color: #4caf50;
}

.delete-button {
  color: #f44336;
}

/* Estilos del modal */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: #fff;
  padding: 20px;
  border-radius: 5px;
  max-width: 400px;
  width: 100%;
  text-align: center;
}

/* Contenedor de icono */
.icon-container {
  display: flex;
  justify-content: center;
  margin-bottom: 20px;
}

.person-icon {
  font-size: 60px;
  color: #333;
}

/* Estilos del formulario */
.form-group {
  margin-bottom: 15px;
  text-align: left;
}

label {
  font-weight: bold;
  margin-bottom: 5px;
  display: block;
}

input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

/* Estilos de los botones del modal */
.modal-footer {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.cancel-button {
  background-color: #f44336;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.submit-button {
  background-color: #4caf50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

/* Botón de agregar */
.add-button {
  background-color: #0047AB;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
  margin-top: 20px;
  display: block;
  margin-left: auto;
  margin-right: auto;
}
</style>