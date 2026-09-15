@extends('sisgedi::layouts.partials.sidebarcomercial')

@section('content')
<div x-data="portafolio()" class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-xl shadow-sm border border-slate-100">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Portafolio de Productos</h1>
            <p class="text-sm text-slate-500 mt-1">Gestiona los productos, aliados y estado del portafolio comercial.</p>
        </div>
        <button @click="openModal('create')" class="bg-[#39A900] hover:bg-[#2e8a00] text-white px-5 py-2.5 rounded-lg font-medium transition-colors flex items-center gap-2 shadow-md shadow-[#39A900]/20">
            <i class="fas fa-plus"></i>
            Agregar Producto
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
            <i class="fas fa-search absolute left-3 top-3.5 text-slate-400"></i>
            <input type="text" x-model="filters.search" placeholder="Buscar por nombre de producto..." class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#39A900] focus:border-[#39A900] outline-none text-sm transition-all">
        </div>
        <select x-model="filters.category" class="px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#39A900] outline-none bg-white text-sm transition-all w-full md:w-56">
            <option value="">Todas las categorías</option>
            <option value="Producción del centro">Producción del centro</option>
            <option value="Emprendedores">Emprendedores</option>
            <option value="Asociaciones campesinas">Asociaciones campesinas</option>
            <option value="Egresados">Egresados</option>
            <option value="Talentos SENA">Talentos SENA</option>
        </select>
        <select x-model="filters.status" class="px-4 py-2.5 border border-slate-200 rounded-lg focus:ring-2 focus:ring-[#39A900] outline-none bg-white text-sm transition-all w-full md:w-48">
            <option value="">Todos los estados</option>
            <option value="Activo">Activo</option>
            <option value="Inactivo">Inactivo</option>
        </select>
    </div>

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="producto in filteredProductos" :key="producto.id">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-200 flex flex-col group">
                <div class="p-6 flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold tracking-wide"
                              :class="{
                                  'bg-blue-50 text-blue-700 border border-blue-200': producto.categoria === 'Producción del centro',
                                  'bg-purple-50 text-purple-700 border border-purple-200': producto.categoria === 'Emprendedores',
                                  'bg-orange-50 text-orange-700 border border-orange-200': producto.categoria === 'Asociaciones campesinas',
                                  'bg-teal-50 text-teal-700 border border-teal-200': producto.categoria === 'Egresados',
                                  'bg-[#39A900]/10 text-[#39A900] border border-[#39A900]/30': producto.categoria === 'Talentos SENA'
                              }" x-text="producto.categoria"></span>
                        
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-md border"
                              :class="producto.estado === 'Activo' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200'">
                            <span class="w-1.5 h-1.5 rounded-full" :class="producto.estado === 'Activo' ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                            <span x-text="producto.estado"></span>
                        </span>
                    </div>
                    
                    <h3 class="text-lg font-bold text-slate-800 mb-1 group-hover:text-[#39A900] transition-colors" x-text="producto.nombre"></h3>
                    <p class="text-[#39A900] font-black text-2xl mb-3" x-text="formatMoney(producto.precio)"></p>
                    
                    <p class="text-sm text-slate-500 line-clamp-2 mb-5 leading-relaxed" x-text="producto.descripcion"></p>
                    
                    <div class="bg-slate-50 rounded-lg p-3 mt-auto border border-slate-100">
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mb-1">Origen / Aliado</p>
                        <div class="flex items-center gap-2">
                            <i class="fas" :class="producto.origen === 'Producción propia' ? 'fa-industry text-[#39A900]' : 'fa-handshake text-indigo-500'"></i>
                            <span class="text-sm font-semibold text-slate-700" x-text="producto.origen === 'Producción propia' ? 'Producción propia' : producto.aliado"></span>
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-slate-100 p-4 bg-slate-50 flex justify-end gap-2">
                    <button @click="openModal('edit', producto)" class="text-slate-500 hover:text-blue-600 bg-white border border-slate-200 hover:border-blue-200 transition-all px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-semibold shadow-sm">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button @click="confirmDelete(producto)" class="text-slate-500 hover:text-rose-600 bg-white border border-slate-200 hover:border-rose-200 transition-all px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-semibold shadow-sm">
                        <i class="fas fa-power-off"></i> Desactivar
                    </button>
                </div>
            </div>
        </template>
        
        <!-- Empty State -->
        <div x-show="filteredProductos.length === 0" class="col-span-full py-16 bg-white rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-5 text-[#39A900]/40">
                <i class="fas fa-box-open text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800">No se encontraron productos</h3>
            <p class="text-slate-500 mt-2 max-w-md">Ajusta los filtros de búsqueda o agrega un nuevo producto al portafolio usando el botón superior.</p>
        </div>
    </div>

    <!-- Modal Form -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="isModalOpen = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="isModalOpen" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full border border-slate-100">
                <form @submit.prevent="saveProducto">
                    <div class="bg-white px-6 pt-6 pb-6">
                        <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-3" id="modal-title">
                                <span class="w-8 h-8 rounded-lg bg-[#39A900]/10 text-[#39A900] flex items-center justify-center">
                                    <i class="fas fa-box"></i>
                                </span>
                                <span x-text="modalMode === 'create' ? 'Agregar Nuevo Producto' : 'Editar Producto'"></span>
                            </h3>
                            <button type="button" @click="isModalOpen = false" class="text-slate-400 hover:text-slate-600 bg-slate-50 hover:bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Nombre -->
                                <div class="col-span-full">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Nombre del producto *</label>
                                    <input type="text" x-model="form.nombre" required placeholder="Ej: Café Tostado Premium" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#39A900]/50 focus:border-[#39A900] outline-none transition-all">
                                </div>
                                
                                <!-- Categoría -->
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Categoría *</label>
                                    <select x-model="form.categoria" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#39A900]/50 focus:border-[#39A900] outline-none bg-white transition-all">
                                        <option value="">Seleccione una categoría...</option>
                                        <option value="Producción del centro">Producción del centro</option>
                                        <option value="Emprendedores">Emprendedores</option>
                                        <option value="Asociaciones campesinas">Asociaciones campesinas</option>
                                        <option value="Egresados">Egresados</option>
                                        <option value="Talentos SENA">Talentos SENA</option>
                                    </select>
                                </div>
                                
                                <!-- Precio -->
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Precio de Venta *</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-3 text-slate-500 font-bold">$</span>
                                        <input type="number" x-model="form.precio" required min="0" step="1" placeholder="0" class="w-full pl-9 pr-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#39A900]/50 focus:border-[#39A900] outline-none transition-all">
                                    </div>
                                </div>

                                <!-- Origen -->
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Origen del producto *</label>
                                    <select x-model="form.origen" required class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#39A900]/50 focus:border-[#39A900] outline-none bg-white transition-all">
                                        <option value="">Seleccione el origen...</option>
                                        <option value="Producción propia">Producción propia</option>
                                        <option value="Emprendedor">Emprendedor</option>
                                        <option value="Egresado">Egresado</option>
                                        <option value="Asociación campesina">Asociación campesina</option>
                                        <option value="Talento SENA">Talento SENA</option>
                                    </select>
                                </div>
                                
                                <!-- Aliado -->
                                <div x-show="form.origen !== 'Producción propia' && form.origen !== ''" x-collapse>
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Nombre del Aliado *</label>
                                    <input type="text" x-model="form.aliado" :required="form.origen !== 'Producción propia'" placeholder="Ej: AsoMiel Huila" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#39A900]/50 focus:border-[#39A900] outline-none transition-all">
                                </div>

                                <!-- Descripción -->
                                <div class="col-span-full">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Descripción</label>
                                    <textarea x-model="form.descripcion" rows="3" placeholder="Describe los detalles del producto..." class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#39A900]/50 focus:border-[#39A900] outline-none transition-all resize-none"></textarea>
                                </div>

                                <!-- Estado (Switch) -->
                                <div class="col-span-full flex items-center justify-between bg-slate-50 p-4 rounded-xl border border-slate-200 mt-2">
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-800">Estado del producto</h4>
                                        <p class="text-xs text-slate-500 mt-0.5">Los productos inactivos se ocultarán del catálogo comercial.</p>
                                    </div>
                                    <button type="button" 
                                            @click="form.estado = form.estado === 'Activo' ? 'Inactivo' : 'Activo'"
                                            class="relative inline-flex h-7 w-12 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-300 ease-in-out focus:outline-none shadow-inner"
                                            :class="form.estado === 'Activo' ? 'bg-[#39A900]' : 'bg-slate-300'">
                                        <span class="sr-only">Cambiar estado</span>
                                        <span class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow-md ring-0 transition duration-300 ease-in-out"
                                              :class="form.estado === 'Activo' ? 'translate-x-5' : 'translate-x-0'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row-reverse gap-3 rounded-b-2xl border-t border-slate-100">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center rounded-lg px-6 py-2.5 bg-[#39A900] text-sm font-bold text-white hover:bg-[#2e8a00] transition-colors shadow-md shadow-[#39A900]/20">
                            <i class="fas fa-save mr-2"></i> Guardar Producto
                        </button>
                        <button type="button" @click="isModalOpen = false" class="w-full sm:w-auto inline-flex justify-center items-center rounded-lg px-6 py-2.5 bg-white text-sm font-bold text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors shadow-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="isDeleteModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="isDeleteModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="isDeleteModalOpen = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="isDeleteModalOpen" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100">
                <div class="bg-white px-6 pt-6 pb-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-rose-100 sm:mx-0 sm:h-12 sm:w-12">
                            <i class="fas fa-exclamation-triangle text-xl text-rose-600"></i>
                        </div>
                        <div class="mt-4 text-center sm:mt-0 sm:ml-5 sm:text-left w-full">
                            <h3 class="text-xl font-bold text-slate-800" id="modal-title">
                                Desactivar Producto
                            </h3>
                            <div class="mt-3">
                                <p class="text-sm text-slate-600 leading-relaxed">
                                    ¿Estás seguro que deseas desactivar el producto <strong class="text-slate-900" x-text="productoToDelete?.nombre"></strong>? Este dejará de ser visible en los catálogos públicos.
                                </p>
                                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3 items-start">
                                    <i class="fas fa-info-circle text-amber-500 mt-0.5 text-lg"></i>
                                    <p class="text-xs text-amber-800 leading-relaxed">
                                        <strong class="block mb-1 text-sm">Advertencia de Inventario</strong>
                                        Si el producto aún tiene stock disponible en los centros de formación, deberás realizar el traslado o baja correspondiente en el módulo de inventario primero.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-col-reverse sm:flex-row-reverse gap-3 rounded-b-2xl border-t border-slate-100">
                    <button type="button" @click="executeDelete()" class="w-full sm:w-auto inline-flex justify-center items-center rounded-lg px-6 py-2.5 bg-rose-600 text-sm font-bold text-white hover:bg-rose-700 transition-colors shadow-md shadow-rose-600/20">
                        <i class="fas fa-power-off mr-2"></i> Sí, Desactivar
                    </button>
                    <button type="button" @click="isDeleteModalOpen = false" class="w-full sm:w-auto inline-flex justify-center items-center rounded-lg px-6 py-2.5 bg-white text-sm font-bold text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors shadow-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('portafolio', () => ({
            productos: [],
            filters: {
                search: '',
                category: '',
                status: ''
            },
            isModalOpen: false,
            isDeleteModalOpen: false,
            modalMode: 'create', // create, edit
            productoToDelete: null,
            form: {
                id: null,
                nombre: '',
                categoria: '',
                precio: '',
                descripcion: '',
                origen: '',
                aliado: '',
                estado: 'Activo'
            },
            
            get filteredProductos() {
                return this.productos.filter(p => {
                    const matchSearch = p.nombre.toLowerCase().includes(this.filters.search.toLowerCase());
                    const matchCategory = this.filters.category === '' || p.categoria === this.filters.category;
                    const matchStatus = this.filters.status === '' || p.estado === this.filters.status;
                    return matchSearch && matchCategory && matchStatus;
                });
            },
            
            formatMoney(value) {
                return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(value);
            },
            
            openModal(mode, producto = null) {
                this.modalMode = mode;
                if (mode === 'edit' && producto) {
                    this.form = { ...producto };
                } else {
                    this.form = { id: null, nombre: '', categoria: '', precio: '', descripcion: '', origen: '', aliado: '', estado: 'Activo' };
                }
                this.isModalOpen = true;
            },
            
            saveProducto() {
                if (this.modalMode === 'create') {
                    this.form.id = Date.now();
                    this.productos.unshift({ ...this.form });
                } else {
                    const index = this.productos.findIndex(p => p.id === this.form.id);
                    if (index !== -1) {
                        this.productos[index] = { ...this.form };
                    }
                }
                this.isModalOpen = false;
            },
            
            confirmDelete(producto) {
                this.productoToDelete = producto;
                this.isDeleteModalOpen = true;
            },
            
            executeDelete() {
                if (this.productoToDelete) {
                    const index = this.productos.findIndex(p => p.id === this.productoToDelete.id);
                    if (index !== -1) {
                        this.productos[index].estado = 'Inactivo';
                    }
                }
                this.isDeleteModalOpen = false;
            }
        }));
    });
</script>
@endsection