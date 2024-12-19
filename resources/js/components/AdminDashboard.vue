<template>
    <div class="dashboard flex flex-col min-h-screen">
        <header class="dashboard-header">
            <img src="/images/logo_c.png" alt="Logo Botica" class="logo" @click="$router.push('/admin-dashboard')" style="cursor: pointer;">
            
            <nav class="dashboard-nav">
                <button @click="$router.push({ path: '/admin-dashboard/vendedores' })">Vendedores</button>
                <button @click="$router.push({ path: '/admin-dashboard/laboratorios' })">Proveedores</button>
                <button @click="$router.push({ path: '/admin-dashboard/generar-reportes' })">Generar Reportes</button>
                 <!--<button @click="showMessage('ajustarStock')">Ajustar Stock</button>-->
            </nav>

            <div class="spotify-player">
                <button @click="togglePlayer" class="player-toggle">
                    <span class="svgContainer">
                        <svg
                            viewBox="0 0 496 512"
                            height="1.6em"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="#1db954"
                        >
                            <path
                                d="M248 8C111.1 8 0 119.1 0 256s111.1 248 248 248 248-111.1 248-248S384.9 8 248 8zm100.7 364.9c-4.2 0-6.8-1.3-10.7-3.6-62.4-37.6-135-39.2-206.7-24.5-3.9 1-9 2.6-11.9 2.6-9.7 0-15.8-7.7-15.8-15.8 0-10.3 6.1-15.2 13.6-16.8 81.9-18.1 165.6-16.5 237 26.2 6.1 3.9 9.7 7.4 9.7 16.5s-7.1 15.4-15.2 15.4zm26.9-65.6c-5.2 0-8.7-2.3-12.3-4.2-62.5-37-155.7-51.9-238.6-29.4-4.8 1.3-7.4 2.6-11.9 2.6-10.7 0-19.4-8.7-19.4-19.4s5.2-17.8 15.5-20.7c27.8-7.8 56.2-13.6 97.8-13.6 64.9 0 127.6 16.1 177 45.5 8.1 4.8 11.3 11 11.3 19.7-.1 10.8-8.5 19.5-19.4 19.5zm31-76.2c-5.2 0-8.4-1.3-12.9-3.9-71.2-42.5-198.5-52.7-280.9-29.7-3.6 1-8.1 2.6-12.9 2.6-13.2 0-23.3-10.3-23.3-23.6 0-13.6 8.4-21.3 17.4-23.9 35.2-10.3 74.6-15.2 117.5-15.2 73 0 149.5 15.2 205.4 47.8 7.8 4.5 12.9 10.7 12.9 22.6 0 13.6-11 23.3-23.2 23.3z"
                            ></path>
                        </svg>
                    </span>
                <span class="BG"></span>
                </button>
                <iframe 
                    v-if="showPlayer"
                    src="https://open.spotify.com/embed/playlist/37i9dQZF1DXcBWIGoYBM5M?utm_source=generator&theme=0" 
                    width="300" 
                    height="80" 
                    frameBorder="0" 
                    style="min-width: 300px; min-height: 80px;"
                    allowtransparency="true"
                    allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                    loading="lazy"
                    class="spotify-iframe"
                ></iframe>
            </div>

            <div class="radio-player">
                <button @click="toggleRadio" class="player-toggle">
                    <span class="svgContainer">
                        <svg
                            viewBox="0 0 24 24"
                            height="1.6em"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="#ff4b4b"
                        >
                            <path d="M3.24 6.15C2.51 6.43 2 7.17 2 8v12c0 1.1.89 2 2 2h16c1.11 0 2-.9 2-2V8c0-1.11-.89-2-2-2H8.3l8.26-3.34L15.88 1 3.24 6.15zM7 20c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm13-8h-2v-2h-2v2H4V8h16v4z"/>
                        </svg>
                    </span>
                </button>
                <iframe 
                    v-if="showRadio"
                    src="https://radio.rpp.pe/player/17"
                    width="300" 
                    height="80" 
                    frameborder="0"
                    class="radio-iframe"
                    allow="autoplay"
                ></iframe>
            </div>


            <!--<input type="text" placeholder="Buscar..." class="search-bar" />-->
            <div class="user-options">
                <i 
                    class="fas fa-bell notification-icon" 
                    @mouseenter="openDropdown" 
                    @mouseleave="closeDropdown"
                ></i>
                <transition name="fade"> 
                    <div  
                        v-if="isDropdownOpen" 
                        class="notification-dropdown" 
                        @mouseenter="keepDropdownOpen"
                        @mouseleave="closeDropdown"
                    >
                        <ul>
                            <li v-for="medicamento in medicamentos" :key="medicamento.id">
                                <span class="alert-icon">⚠️</span>
                                <span class="medicamento-nombre">{{ medicamento.Nombre }}</span>
                                <span class="medicamento-stock">Stock: {{ medicamento.Stock }}</span>
                            </li>
                        </ul>
                    </div>
                </transition>
                <i class="fas fa-user-circle user-icon" @click="$router.push({ path: '/admin-dashboard/perfil' })"></i>
            </div>
        </header>

        <!-- Segunda cabecera para los botones adicionales -->
        <header class="sub-header">
            <button @click="$router.push({ path: '/admin-dashboard/registrar-venta' })"><span class="key-button">F4</span>
                Vender</button>
            <button @click="$router.push({ path: '/admin-dashboard/registrar-compra' })"><span class="key-button">F5</span>
                Comprar Productos</button>
            <!--<button @click="showMessage('listaMedicamentos')">Lista de Medicamentos</button>-->
            <button @click="$router.push({ path: '/admin-dashboard/medicamentos' })"><span class="key-button">F6</span>
                Productos Disponibles</button>
        </header>

        <main class="dashboard-content">
            <!-- Mostrar esta sección solo si no estamos en la ruta "Vendedores" -->
            <section class="main-section" v-if="$route.name !== 'Vendedores' && 
                                 $route.name !== 'Laboratorios' && 
                                 $route.name !== 'Medicamento' && 
                                 $route.name !== 'RegistrarCompra' && 
                                 $route.name !== 'RegistrarVenta' && 
                                 $route.name !== 'PerfilVendedor'&& 
                                 $route.name !== 'GenerarReportes'"  >
                <div class="action-container">
                    <div class="action-icon" @click="openModal('publicarMedicamento')">
                        <img src="/images/publish_icon.png" alt="Publicar Medicamento" class="icon-image" />
                        <p>Publicar un Medicamento</p>
                    </div>
                    <div class="action-icon" @click="showMessage('entrevistasContratos')">
                        <img src="/images/contracts_icon.png" alt="Entrevistas y Contratos" class="icon-image" />
                        <p>Entrevistas y Contratos</p>
                    </div>
                </div>
                <button class="publish-button" @click="openModal('publicarMedicamento')">Publicar un Medicamento</button>
            </section>

            <!-- Área principal para cargar el componente de "Vendedores" -->
            <section class="main-section" v-if="$route.name === 'Vendedores'">
                <router-view></router-view> <!-- Aquí se mostrarán los componentes hijos como Vendedores -->
            </section>
            <!-- Área principal para cargar el componente de "Laboratorios" -->
            <section class="main-section" v-if="$route.name === 'Laboratorios'">
                <router-view></router-view> <!-- Aquí se mostrarán los componentes hijos como Vendedores -->
            </section>

                        <!-- Área principal para cargar el componente de "Laboratorios" -->
            <section class="main-section" v-if="$route.name === 'Medicamento'">
                <router-view></router-view> <!-- Aquí se mostrarán los componentes hijos como Vendedores -->
            </section>

    <!-- Mostrar Registrar Compra -->
            <section class="main-section" v-else-if="$route.name === 'RegistrarCompra'">
                <router-view></router-view>
            </section>
    <!-- Mostrar Registrar Venta  -->
           <section class="main-section" v-else-if="$route.name === 'RegistrarVenta'">
                <router-view></router-view>
            </section>

            <section class="main-section" v-if="$route.name === 'PerfilVendedor'">
                <router-view></router-view>
            </section>

              <!-- Mostrar GenerarReportes -->
            <section class="main-section" v-else-if="$route.name === 'GenerarReportes'">
                <router-view></router-view>
            </section>

            <aside class="faq-section" v-if="!['Vendedores', 'Laboratorios', 'Medicamento', 'RegistrarCompra', 'RegistrarVenta', 'PerfilVendedor', 'GenerarReportes'].includes($route.name)">
                <h3>Preguntas</h3>
                <ul>
                    <li>¿Cómo sé que estoy protegido?</li>
                    <li>¿Cómo paga?</li>
                    <li>¿Cómo empiezo?</li>
                    <li><a href="#">Más &gt;</a></li>
                </ul>

            </aside>
        </main>

        <FormularioMedicamento 
            :isOpen="modalOpen"
            @close-modal="closeModal" 
            @add-medicamento="handleAddMedicamento"
        />

    </div>
</template>


<script>
import FormularioMedicamento from '@/components/FormularioMedicamento.vue';  // Usando alias '@' para acceder a 'src/components'
export default {
    
    components: {
        FormularioMedicamento
    },

    data() {
        return {
            showPlayer: false,
            isDropdownOpen: false,
            modalOpen: false,  // Estado para controlar la visibilidad del modal
            medicamento: null,
            medicamentos: [],
            showRadio: false,         // Aquí puedes almacenar el medicamento que se está publicando
        };
    },

    mounted() {
        // Agregar el evento de teclado cuando se monta el componente
        document.addEventListener('keydown', this.handleKeyPress);
    },
    beforeUnmount() {
        // Remover el evento de teclado cuando se destruye el componente
        document.removeEventListener('keydown', this.handleKeyPress);
    },

    methods: {
    // Método para abrir el modal
    togglePlayer() {
      this.showPlayer = !this.showPlayer;
    },

    toggleRadio() {
        this.showRadio = !this.showRadio;
    },

    handleKeyPress(event) {
    // Verifica si estamos en la ruta del admin-dashboard
    if (this.$route.path.startsWith('/admin-dashboard')) {
        if (event.key === "F4") {
            event.preventDefault();
            event.stopPropagation();
            this.$router.push({ path: '/admin-dashboard/registrar-venta' });
        } else if (event.key === "F5") {
            event.preventDefault();
            event.stopPropagation();
            this.$router.push({ path: '/admin-dashboard/registrar-compra' });
        } else if (event.key === "F6") {
            event.preventDefault();
            event.stopPropagation();
            this.$router.push({ path: '/admin-dashboard/medicamentos' });
        }
    }
},


    openDropdown() {
            this.isDropdownOpen = true;
            this.fetchMedicamentosBajo();
        },
        closeDropdown() {
            this.isDropdownOpen = false;
        },
        keepDropdownOpen() {
            // Mantiene abierto el desplegable si el cursor está dentro del menú
            this.isDropdownOpen = true;
        },
        fetchMedicamentosBajo() {
            axios.get('/medicamentos/bajo')
                .then(response => {
                    this.medicamentos = response.data;
                })
                .catch(error => {
                    console.error("Error al obtener medicamentos bajos:", error);
                });
        },

    openModal(action) {
        if (action === 'publicarMedicamento') {
            this.modalOpen = true;  // Abrir el modal cuando se haga clic en 'Publicar Medicamento'
        }
    },

    // Método para cerrar el modal
    closeModal() {
        this.modalOpen = false;  // Cerrar el modal
    },

    handleAddMedicamento(medicamento) {
      // Maneja la lógica para agregar el medicamento
      console.log('Medicamento agregado:', medicamento);
      this.closeModal();  // Cierra el modal después de agregar el medicamento
    },

    // Otros métodos para la navegación
    showMessage(action) {
        if (action === 'verVendedores') {
            this.$router.push({ name: 'Vendedores' });
        } else if(action === 'verLaboratorios'){
            this.$router.push({ name: 'Laboratorios' });
        } else if (action === 'listaMedicamentos') {
            this.$router.push({ name: 'Medicamento' });
        } else if (action === 'registrarCompra') {
            this.$router.push({ path: '/admin-dashboard/registrar-compra' });
        } else if (action === 'registrarVenta') {
            this.$router.push({ path: '/admin-dashboard/registrar-venta' });
        } else {
            const messages = {
                verProveedores: "Moviendo a pestaña de proveedores",
                descargarReportes: "Descargando reportes",
                ajustarStock: "Ajustando stock",
                realizarVenta: "Moviendo a realizar venta",
                publicarMedicamento: "Moviendo a publicar medicamento",
                entrevistasContratos: "Moviendo a entrevistas y contratos",
            };
            console.log(messages[action]);
            alert(messages[action]);
        }
    },
}

};
</script>

<style scoped>
.dashboard {
    display: flex;
    flex-direction: column;
    font-family: Arial, sans-serif;
    /* No se aplica background aquí */
}

.dashboard-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
    color: #333;
}

.dashboard-header .dashboard-nav {
    display: flex;
    gap: 8px;
}

.dashboard-header .dashboard-nav button {
    background: transparent;
    border: 2px solid transparent;
    color: #333;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    padding: 8px 15px;
    transition: all 0.2s ease;
    border-radius: 5px;
    position: relative;
}

.dashboard-header .dashboard-nav button.active {
    background-color: #A62103;
    color: white;
    box-shadow: 0 2px 4px rgba(155, 155, 155, 0.681);
}

.dashboard-header .dashboard-nav button:not(.active):hover {
    background-color: rgba(220, 220, 220, 0.681);
    color: #000;
}

.sub-header {
    display: flex;
    justify-content: space-around;
    background-color: #333;
    padding: 10px 0;
}

.sub-header button {
    background: transparent;
    border: none;
    color: #fff;
    cursor: pointer;
    font-size: 14px;
    font-weight: bold;
    padding: 8px 15px;
    transition: all 0.2s ease;
    border-radius: 5px;
    opacity: 0.8;
}

.sub-header button.active {
    background-color: #A62103;
    color: white;
    opacity: 1;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.sub-header button:not(.active):hover {
    background-color: rgba(155, 155, 155, 0.681);
    opacity: 1;
}

.publish-button {
  background-color: #A62103;
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

.logo {
    width: 50px;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.logo:hover {
    transform: scale(1.05);
}

.user-options {
    display: flex;
    gap: 15px;
}

.user-options i {
    font-size: 20px;
    color: #333;
    cursor: pointer;
    transition: all 0.2s ease;
}

.user-options i:hover {
    color: #A62103;
    transform: scale(1.1);
}

.dashboard-content {
    display: flex;
    padding: 20px;
    height: calc(100vh - 120px);
    overflow: hidden;
}

.main-section {
    flex: 1;
    background-color: white;
    padding: 20px;
    align-items: center; 
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-right: 20px;
    overflow-y: auto;
}

.action-container {
    display: flex;
    justify-content: space-around;
    width: 100%;
    margin-bottom: 20px;
}

.action-icon {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    padding: 15px;
    text-align: center;
    transition: transform 0.2s ease;
}

.action-icon:hover {
    transform: translateY(-5px);
}

.icon-image {
    width: 150px !important;
    height: 150px !important;
    margin-bottom: 10px !important;
    transition: transform 0.2s ease;
}

.publish-button {
    background-color: #333;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 16px;
    border-radius: 5px;
    font-weight: bold;
    margin-top: 10px;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.publish-button:hover {
    background-color: #A62103;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transform: translateY(-2px);
}

.faq-section {
    width: 300px;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    height: fit-content;
}

.faq-section h3 {
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
    color: #333;
    border-bottom: 2px solid #A62103;
    padding-bottom: 8px;
}

.faq-section ul {
    list-style: none;
    padding: 0;
    font-size: 14px;
}

.faq-section ul li {
    margin-bottom: 12px;
    padding: 8px;
    border-radius: 4px;
    transition: background-color 0.2s ease;
}

.faq-section ul li:hover {
    background-color: rgba(210, 210, 210, 0.681);
}

.faq-section ul li a {
    color: #333;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.2s ease;
}

.faq-section ul li a:hover {
    color: #A62103;
}

@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        gap: 10px;
    }

    .dashboard-nav {
        width: 100%;
        flex-wrap: wrap;
        justify-content: center;
    }

    .dashboard-content {
        flex-direction: column;
    }

    .main-section {
        margin-right: 0;
        margin-bottom: 20px;
    }

    .faq-section {
        width: 100%;
    }
}
.notification-dropdown {
    position: absolute;
    top: 10px; /* Lo bajamos un poco */
    right: 90px; /* Lo movemos más a la izquierda */
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    width: 250px;
    max-height: 250px;
    overflow-y: auto;
    z-index: 1000;
    padding: 5px 0;
    transition: opacity 0.3s ease;
}

/* Agregamos un pequeño triángulo apuntando al ícono */
.notification-dropdown::before {
    content: '';
    position: absolute;
    top: -10px;
    right: 20px; /* Ajusta para que quede centrado con el ícono */
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid #ddd; /* Triángulo borde */
    z-index: 1001;
}

.notification-dropdown::after {
    content: '';
    position: absolute;
    top: -9px;
    right: 21px; /* Ajusta para que quede centrado con el ícono */
    width: 0;
    height: 0;
    border-left: 9px solid transparent;
    border-right: 9px solid transparent;
    border-bottom: 9px solid white; /* Triángulo fondo */
    z-index: 1002;
}


.notification-dropdown ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.notification-dropdown li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 10px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 13px; /* Más pequeño */
    color: #333;
    transition: background-color 0.3s;
}

.notification-dropdown li:hover {
    background-color: #ffe6e6; /* Fondo rojo claro en hover */
}

.notification-dropdown li:last-child {
    border-bottom: none;
}

.alert-icon {
    color: #e74c3c; /* Rojo de alerta */
    font-size: 14px;
    margin-right: 8px;
}

.medicamento-nombre {
    flex: 1;
    font-weight: bold;
    color: #e74c3c; /* Texto rojo de alerta */
}

.medicamento-stock {
    color: #555;
    font-size: 12px;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}



.key-button {
    background: linear-gradient(-225deg, #d5dbe4, #f8f8f8); /* Gradiente similar a teclas */
    border: 1px solid #cdcde6;
    border-radius: 4px;
    box-shadow: inset 0 -2px 0 0 #cdcde6, inset 0 0 1px 1px #fff,
        0 1px 2px 1px rgba(30, 35, 90, 0.4);
    color: #555;
    font-size: 0.9em;
    font-weight: bold;
    padding: 3px 6px;
    margin-right: 8px; /* Espacio entre la tecla y el texto */
    text-transform: uppercase;
    display: inline-block;
    user-select: none; /* Evita que se seleccione */
}

.spotify-player {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 15px;
}

.player-toggle {
    background: none;
    border: 1px solid #e0e0e0;
    color: #333;
    cursor: pointer;
    padding: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.player-toggle:hover {
    color: #4CAF50;
    background-color: rgba(76, 175, 80, 0.1);
    transform: scale(1.05);
}

.spotify-iframe {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    max-height: 80px;
    background-color: #282828;
}
/* Ajusta el header para acomodar el reproductor */
.dashboard-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 20px;
    gap: 10px;
}


.radio-player {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0 15px;
}

.radio-iframe {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    max-height: 80px;
    background-color: #282828;
}
</style>