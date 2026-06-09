@extends('layouts.admin')

@push('styles')
    <!-- Google Fonts for premium typography -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome for robust icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: {
                preflight: false, // Turn off preflight to avoid conflicting with existing LMS Admin styles
            },
            theme: {
                extend: {
                    colors: {
                        slate: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Hide template left sidebar and top navbar */
        .app-sidebar, 
        .doc-sidebar, 
        .app-header1, 
        .header {
            display: none !important;
        }

        /* Adjust main page-content container to fill full-width space */
        .app-content {
            margin-left: 0 !important;
            padding-top: 1.5rem !important;
            padding-bottom: 3rem !important;
            min-height: 100vh;
            background-color: #f8fafc !important; /* light slate background */
        }

        /* Reset layout spacing classes */
        body, 
        .app, 
        .page,
        .page-main {
            padding-left: 0 !important;
            background-color: #f8fafc !important;
        }

        /* Modern styles overriding defaults */
        .form-builder-app {
            font-family: 'Outfit', sans-serif !important;
        }
        .sortable-ghost {
            opacity: 0.35;
            background-color: #eff6ff !important;
            border: 2px dashed #3b82f6 !important;
            transform: scale(0.98);
        }
        .sortable-chosen {
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.2) !important;
        }
        /* Hide scrollbar for aesthetics */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endpush

@section('content')
<div class="app-content form-builder-app text-slate-800 antialiased" x-data="formBuilder()" x-init="init()">
    <div class="side-app max-w-7xl mx-auto px-4 py-6">
        
        <!-- Header / Form Title Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <!-- Left: Title and URL inputs -->
                <div class="flex-1">
                    <div class="relative max-w-md">
                        <input 
                            type="text" 
                            x-model="title" 
                            maxlength="200"
                            class="w-full text-2xl font-bold text-slate-800 border-b border-transparent hover:border-slate-300 focus:border-indigo-500 focus:outline-none pb-1 transition-all"
                            placeholder="Enter Form Title..."
                        />
                        <!-- Live counter -->
                        <span 
                            class="absolute right-2 bottom-1.5 text-xxs font-medium text-slate-400"
                            x-text="title.length + '/200'"
                        ></span>
                    </div>
                    <div class="flex items-center gap-2 mt-2 text-xs text-slate-400">
                        <span class="font-mono bg-slate-50 px-2 py-0.5 rounded border border-slate-100">POST Target:</span>
                        <span x-text="submissionUrl" class="truncate max-w-xs font-medium"></span>
                    </div>
                </div>

                <!-- Right: Active Mode Tabs & Workspace Controls -->
                <div class="flex items-center justify-between md:justify-end gap-4 border-t md:border-t-0 pt-4 md:pt-0">
                    <!-- Tabs: Editor vs Settings -->
                    <div class="flex bg-slate-100 p-1 rounded-xl">
                        <button 
                            @click="activeTab = 'editor'" 
                            :class="activeTab === 'editor' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                            class="px-4 py-2 text-xs font-semibold rounded-lg transition-all"
                        >
                            Form Editor
                        </button>
                        <button 
                            @click="activeTab = 'settings'" 
                            :class="activeTab === 'settings' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-800'"
                            class="px-4 py-2 text-xs font-semibold rounded-lg transition-all"
                        >
                            Settings
                        </button>
                    </div>



                    <!-- Undo / Redo controls -->
                    <div class="flex items-center gap-1 border-l border-slate-200 pl-3">
                        <button 
                            @click="undo()" 
                            :disabled="historyIndex <= 0"
                            class="p-2 rounded-lg text-slate-400 hover:text-slate-700 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-50 transition-all"
                            title="Undo (Ctrl+Z)"
                        >
                            <i class="fa fa-undo"></i>
                        </button>
                        <button 
                            @click="redo()" 
                            :disabled="historyIndex >= history.length - 1"
                            class="p-2 rounded-lg text-slate-400 hover:text-slate-700 disabled:opacity-30 disabled:cursor-not-allowed hover:bg-slate-50 transition-all"
                            title="Redo (Ctrl+Y)"
                        >
                            <i class="fa fa-repeat"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings tab template -->
        <div x-show="activeTab === 'settings'" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Form Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-3xl">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Form Submission URL</label>
                    <input 
                        type="url" 
                        x-model="submissionUrl" 
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm transition-all"
                    />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Submit Button Label</label>
                    <input 
                        type="text" 
                        x-model="settings.submitLabel" 
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm transition-all"
                    />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Submission Success Message</label>
                    <textarea 
                        x-model="settings.successMessage" 
                        rows="3"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none text-sm transition-all"
                    ></textarea>
                </div>
            </div>
        </div>

        <!-- Form Editor Panel Layout (activeTab = editor) -->
        <div x-show="activeTab === 'editor'" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- LEFT COLUMN: Canvas Drop Zone (8 Cols) -->
            <div class="lg:col-span-8 flex flex-col">
                
                <!-- Editor/Preview Title Card -->
                <div class="bg-indigo-900 rounded-t-2xl p-4 flex items-center justify-between text-white shadow-sm">
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-2 rounded-full bg-amber-400"></div>
                        <span class="text-xs font-semibold uppercase tracking-wider">Interactive Layout Editor</span>
                    </div>
                    
                    <button 
                        @click="clearForm()" 
                        x-show="fields.length > 0"
                        class="text-xs font-semibold text-indigo-200 hover:text-white flex items-center gap-1.5 transition-all bg-indigo-950/40 px-3 py-1 rounded-lg border border-indigo-700/50"
                    >
                        <i class="fa fa-trash-o"></i> Clear Canvas
                    </button>
                </div>

                <!-- Canvas Dropzone Container -->
                <div 
                    id="canvas-container"
                    class="bg-white rounded-b-2xl border-x border-b border-slate-100 p-6 min-h-[480px] flex flex-col relative shadow-sm transition-all duration-200"
                    :class="isDraggingOver ? 'ring-2 ring-indigo-500 border-indigo-500' : ''"
                >
                    <!-- Empty State -->
                    <div 
                        x-show="fields.length === 0" 
                        class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center select-none pointer-events-none z-0"
                    >
                        <div class="h-16 w-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800">Your Form is Empty</h4>
                        <p class="text-xs text-slate-400 max-w-[280px] mt-1">
                            Drag elements from the right panel to build your form →
                        </p>
                    </div>

                    <!-- Placed Fields List (Grid layout to support col-span wrapper classes) -->
                    <div 
                        id="canvas-dropzone" 
                        class="grid grid-cols-12 gap-4 items-start content-start min-h-[440px] flex-1 z-10"
                    >
                        <template x-for="(field, index) in fields" :key="field.id">
                            <!-- Field Wrapper Card -->
                            <div 
                                class="group relative bg-white rounded-xl border border-slate-150 p-4 transition-all duration-200 shadow-sm"
                                :class="[
                                    selectedFieldId === field.id ? 'ring-2 ring-indigo-500 border-transparent shadow-md bg-indigo-50/20' : 'hover:border-slate-300',
                                    field.cssClass || 'col-span-12'
                                ]"
                                @click="editField(field.id)"
                            >
                                <!-- Control Handles -->
                                <div 
                                    class="absolute top-3 right-3 flex items-center bg-white border border-slate-100 shadow-sm rounded-lg overflow-hidden opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity duration-200 z-10"
                                >
                                    <!-- Drag Handle -->
                                    <div class="drag-handle p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-50 cursor-move border-r border-slate-100" title="Move Field">
                                        <i class="fa fa-arrows"></i>
                                    </div>
                                    <!-- Edit Icon -->
                                    <button 
                                        @click.stop="editField(field.id)"
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-slate-50 border-r border-slate-100"
                                        title="Edit Field"
                                    >
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <!-- Duplicate Icon -->
                                    <button 
                                        @click.stop="duplicateField(field.id)"
                                        class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-slate-50 border-r border-slate-100"
                                        title="Duplicate Field"
                                    >
                                        <i class="fa fa-copy"></i>
                                    </button>
                                    <!-- Delete Icon -->
                                    <button 
                                        @click.stop="deleteField(field.id)"
                                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-slate-50"
                                        title="Delete Field"
                                    >
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </div>

                                <!-- Field Label/Placeholder indicators -->
                                <div class="absolute left-3 top-3 bg-slate-100 text-slate-500 rounded px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider select-none pointer-events-none" x-text="field.type"></div>

                                <!-- Content Rendering: Router selects specific field -->
                                <div class="pt-4 pointer-events-auto">
                                    <!-- Server Compiled Blade Components wrapped in dynamic toggles -->
                                    <div x-show="field.type === 'text'"><x-form-field type="text" alpine="true" /></div>
                                    <div x-show="field.type === 'textarea'"><x-form-field type="textarea" alpine="true" /></div>
                                    <div x-show="field.type === 'number'"><x-form-field type="number" alpine="true" /></div>
                                    <div x-show="field.type === 'email'"><x-form-field type="email" alpine="true" /></div>
                                    <div x-show="field.type === 'phone'"><x-form-field type="phone" alpine="true" /></div>
                                    <div x-show="field.type === 'dropdown'"><x-form-field type="dropdown" alpine="true" /></div>
                                    <div x-show="field.type === 'radio'"><x-form-field type="radio" alpine="true" /></div>
                                    <div x-show="field.type === 'checkbox'"><x-form-field type="checkbox" alpine="true" /></div>
                                    <div x-show="field.type === 'date'"><x-form-field type="date" alpine="true" /></div>
                                    <div x-show="field.type === 'file'"><x-form-field type="file" alpine="true" /></div>
                                    <div x-show="field.type === 'title'"><x-form-field type="title" alpine="true" /></div>
                                    <div x-show="field.type === 'description'"><x-form-field type="description" alpine="true" /></div>
                                    <div x-show="field.type === 'newline'"><x-form-field type="newline" alpine="true" /></div>
                                    <div x-show="field.type === 'pagebreak'"><x-form-field type="pagebreak" alpine="true" /></div>
                                    <div x-show="field.type === 'hidden'"><x-form-field type="hidden" alpine="true" /></div>
                                    <div x-show="field.type === 'state'"><x-form-field type="state" alpine="true" /></div>
                                    <div x-show="field.type === 'city'"><x-form-field type="city" alpine="true" /></div>
                                    <div x-show="field.type === 'state_city'"><x-form-field type="state_city" alpine="true" /></div>
                                </div>
                            </div>
                        </template>
                    </div>


                </div>
            </div>

            <!-- RIGHT COLUMN: Palette Panel / Option Configuration (4 Cols) -->
            <div class="lg:col-span-4 sticky top-6">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    
                    <!-- Sidebar Tabs: Add Fields vs Field Options -->
                    <div class="flex border-b border-slate-100 bg-slate-50/50 p-2">
                        <button 
                            @click="activePaletteTab = 'add'" 
                            :class="activePaletteTab === 'add' ? 'bg-white text-indigo-600 shadow-sm border border-slate-100' : 'text-slate-500 hover:text-slate-850'"
                            class="flex-1 py-2 text-xs font-bold rounded-lg transition-all"
                        >
                            Add Fields
                        </button>
                        <button 
                            @click="selectedFieldId ? activePaletteTab = 'options' : showToast('Select a field to configure Options', 'info')" 
                            :class="[
                                activePaletteTab === 'options' ? 'bg-white text-indigo-600 shadow-sm border border-slate-100' : 'text-slate-500 hover:text-slate-850',
                                !selectedFieldId ? 'opacity-50 cursor-not-allowed' : ''
                            ]"
                            class="flex-1 py-2 text-xs font-bold rounded-lg transition-all"
                        >
                            Field Options
                        </button>
                    </div>

                    <div class="p-6 max-h-[580px] overflow-y-auto no-scrollbar">
                        
                        <!-- TAB: Add Fields palette -->
                        <div x-show="activePaletteTab === 'add'">
                            <div class="mb-4">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Standard Inputs</h4>
                                <div id="palette-items" class="palette-grid grid grid-cols-2 gap-3">
                                    <!-- Draggable Tiles -->
                                    <div data-type="text" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-font text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Text Input</span>
                                    </div>
                                    <div data-type="textarea" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-align-left text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Text Area</span>
                                    </div>
                                    <div data-type="number" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-hashtag text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Number Input</span>
                                    </div>
                                    <div data-type="email" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-envelope text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Email Input</span>
                                    </div>
                                    <div data-type="phone" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-phone text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Phone Input</span>
                                    </div>
                                    <div data-type="date" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-calendar text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Date Picker</span>
                                    </div>
                                    <div data-type="file" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-cloud-upload text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">File Upload</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4 mt-6">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Choice Selections</h4>
                                <div class="palette-grid grid grid-cols-2 gap-3">
                                    <!-- Draggable Tiles -->
                                    <div data-type="dropdown" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-caret-square-o-down text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Dropdown</span>
                                    </div>
                                    <div data-type="radio" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-dot-circle-o text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Radio Buttons</span>
                                    </div>
                                    <div data-type="checkbox" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-check-square-o text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Checkboxes</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4 mt-6">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Location Helpers</h4>
                                <div class="palette-grid grid grid-cols-2 gap-3">
                                    <!-- Draggable Tiles -->
                                    <div data-type="state" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-map text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">State</span>
                                    </div>
                                    <div data-type="city" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-building-o text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">City</span>
                                    </div>
                                    <div data-type="state_city" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none col-span-2">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-map-marker text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">State & City Combined</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4 mt-6">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Structure & Layout</h4>
                                <div class="palette-grid grid grid-cols-2 gap-3">
                                    <!-- Draggable Tiles -->
                                    <div data-type="title" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-header text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Title Header</span>
                                    </div>
                                    <div data-type="description" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-paragraph text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Description</span>
                                    </div>
                                    <div data-type="newline" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-minus text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">New Line</span>
                                    </div>
                                    <div data-type="pagebreak" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-columns text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Page Break</span>
                                    </div>
                                    <div data-type="hidden" class="palette-tile group flex flex-col p-3 rounded-xl border border-slate-150 hover:border-indigo-300 bg-white hover:bg-indigo-50/10 cursor-grab active:cursor-grabbing transition-all select-none col-span-2">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 group-hover:bg-indigo-100/50 flex items-center justify-center text-indigo-600 mb-2 transition-all">
                                            <i class="fa fa-eye-slash text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700">Hidden Field</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB: Field Options Editor -->
                        <div x-show="activePaletteTab === 'options'">
                            <template x-if="selectedFieldId && getSelectedField()">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                        <h4 class="text-sm font-bold text-slate-800">
                                            Field Settings: <span class="text-indigo-600 font-mono text-xs uppercase" x-text="getSelectedField().type"></span>
                                        </h4>
                                        <span class="text-xxs text-slate-400 font-mono" x-text="selectedFieldId"></span>
                                    </div>

                                    <!-- Label Option (Applicable to all) -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Field Label</label>
                                        <input 
                                            type="text" 
                                            x-model="getSelectedField().label" 
                                            @input="saveHistory()"
                                            class="w-full px-3 py-2 text-sm rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                        />
                                    </div>

                                    <!-- Placeholder (Text, Area, Number, Email, Phone, Dropdown, State, City) -->
                                    <template x-if="['text', 'textarea', 'number', 'email', 'phone', 'dropdown', 'state', 'city'].includes(getSelectedField().type)">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1">Placeholder Text</label>
                                            <input 
                                                type="text" 
                                                x-model="getSelectedField().placeholder" 
                                                @input="saveHistory()"
                                                class="w-full px-3 py-2 text-sm rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                            />
                                        </div>
                                    </template>

                                    <!-- Default Value (Text, Number, Email, Hidden) -->
                                    <template x-if="['text', 'number', 'email', 'hidden'].includes(getSelectedField().type)">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1">Default Value</label>
                                            <input 
                                                type="text" 
                                                x-model="getSelectedField().defaultValue" 
                                                @input="saveHistory()"
                                                class="w-full px-3 py-2 text-sm rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                            />
                                        </div>
                                    </template>

                                    <!-- Min / Max Limits (Text, Area) -->
                                    <template x-if="['text', 'textarea'].includes(getSelectedField().type)">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-600 mb-1">Min Chars</label>
                                                <input 
                                                    type="number" 
                                                    x-model.number="getSelectedField().min" 
                                                    @input="saveHistory()"
                                                    class="w-full px-3 py-2 text-sm rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                                    min="0"
                                                />
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-slate-600 mb-1">Max Chars</label>
                                                <input 
                                                    type="number" 
                                                    x-model.number="getSelectedField().max" 
                                                    @input="saveHistory()"
                                                    class="w-full px-3 py-2 text-sm rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                                    min="0"
                                                />
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Options List Editor (Dropdown, Radio, Checkbox) -->
                                    <template x-if="['dropdown', 'radio', 'checkbox'].includes(getSelectedField().type)">
                                        <div>
                                            <div class="flex items-center justify-between mb-1.5">
                                                <label class="block text-xs font-semibold text-slate-600">Configure Options</label>
                                                <button 
                                                    @click="addOption(getSelectedField())"
                                                    class="text-xxs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1"
                                                >
                                                    <i class="fa fa-plus"></i> Add Row
                                                </button>
                                            </div>
                                            <div class="space-y-2">
                                                <template x-for="(opt, idx) in getSelectedField().options" :key="idx">
                                                    <div class="flex items-center gap-2">
                                                        <input 
                                                            type="text" 
                                                            :value="opt"
                                                            @input="getSelectedField().options[idx] = $event.target.value; saveHistory()"
                                                            class="flex-1 px-2.5 py-1.5 text-xs rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
                                                        />
                                                        <button 
                                                            @click="removeOption(getSelectedField(), idx)"
                                                            :disabled="getSelectedField().options.length <= 1"
                                                            class="p-2 text-slate-400 hover:text-rose-600 disabled:opacity-30 disabled:cursor-not-allowed transition-all"
                                                        >
                                                            <i class="fa fa-close"></i>
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Required Field Switcher (All except layout structural elements) -->
                                    <template x-if="!['title', 'description', 'newline', 'pagebreak', 'hidden'].includes(getSelectedField().type)">
                                        <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                                            <span class="text-xs font-semibold text-slate-600">Field Mandatory (Required)</span>
                                            <button 
                                                type="button"
                                                @click="getSelectedField().required = !getSelectedField().required; saveHistory()"
                                                :style="getSelectedField().required ? 'background-color: #4f46e5 !important;' : 'background-color: #cbd5e1 !important;'"
                                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-0 p-0 outline-none transition-colors duration-200 ease-in-out"
                                                style="border: none !important; outline: none !important;"
                                            >
                                                <span 
                                                    :style="getSelectedField().required ? 'transform: translateX(1.25rem) !important;' : 'transform: translateX(0.125rem) !important;'"
                                                    class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transition-transform duration-200 ease-in-out mt-0.5"
                                                ></span>
                                            </button>
                                        </div>
                                    </template>

                                    <!-- CSS Class (All fields) -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Custom CSS Wrapper Class</label>
                                        <input 
                                            type="text" 
                                            x-model="getSelectedField().cssClass" 
                                            @input="saveHistory()"
                                            class="w-full px-3 py-2 text-sm rounded-lg border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none font-mono transition-all"
                                            placeholder="e.g. col-span-6"
                                        />
                                    </div>

                                    <!-- Remove Element (All fields) -->
                                    <div class="border-t border-slate-100 pt-4 mt-6">
                                        <button 
                                            @click="deleteField(selectedFieldId)"
                                            class="w-full py-2.5 rounded-xl text-xs font-bold text-white bg-rose-500 hover:bg-rose-600 active:scale-[0.98] transition-all flex items-center justify-center gap-1.5 shadow-md shadow-rose-500/15"
                                        >
                                            <i class="fa fa-trash"></i> Remove Element
                                        </button>
                                    </div>
                                </div>
                            </template>
                            
                            <!-- No field selected fallback -->
                            <div x-show="!selectedFieldId" class="py-12 text-center select-none text-slate-400">
                                <i class="fa fa-sliders text-2xl mb-2"></i>
                                <p class="text-xs">Select a placed element in the canvas to edit its properties.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER ACTIONS BAR -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mt-6 flex items-center justify-between">
            <button 
                @click="resetWholeBuilder()"
                class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-50 active:scale-95 transition-all"
            >
                Cancel / Reset
            </button>
            <button 
                @click="exportSchema()"
                :disabled="fields.length === 0"
                class="px-6 py-2.5 rounded-xl bg-indigo-650 hover:bg-indigo-700 active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed disabled:scale-100 text-xs font-bold text-white shadow-md shadow-indigo-600/15 transition-all"
                style="background-color: #4f46e5;"
            >
                Next / Export Schema
            </button>
        </div>
    </div>

    <!-- MODAL: Serialized JSON Schema Output -->
    <div 
        x-show="isJsonModalOpen" 
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="isJsonModalOpen = false"></div>

        <!-- Modal Box -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div 
                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all w-full max-w-2xl border border-slate-100"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <div class="bg-indigo-900 px-6 py-4 flex items-center justify-between text-white">
                    <div class="flex items-center gap-2">
                        <i class="fa fa-code"></i>
                        <h3 class="text-sm font-bold uppercase tracking-wider">Form JSON Schema Output</h3>
                    </div>
                    <button @click="isJsonModalOpen = false" class="text-indigo-200 hover:text-white transition-colors">
                        <i class="fa fa-close text-lg"></i>
                    </button>
                </div>

                <div class="p-6">
                    <p class="text-xs text-slate-500 mb-3">
                        Here is the serialized JSON representation of your visually-constructed form.
                    </p>
                    <div class="relative bg-slate-900 text-emerald-400 font-mono text-xs rounded-xl p-4 overflow-x-auto max-h-[360px] border border-slate-950">
                        <pre x-text="jsonSchemaOutput" class="no-scrollbar"></pre>
                        <button 
                            @click="copyToClipboard()"
                            class="absolute top-3 right-3 bg-slate-800 hover:bg-slate-700 active:scale-90 text-white rounded-lg px-3 py-1.5 text-xxs font-bold flex items-center gap-1.5 transition-all border border-slate-700/50"
                        >
                            <i class="fa fa-copy"></i> Copy JSON
                        </button>
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl border-t border-slate-100">
                    <button 
                        @click="isJsonModalOpen = false"
                        class="px-4 py-2 border border-slate-200 hover:bg-white text-xs font-bold text-slate-600 rounded-xl transition-all"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATIONS DRAWER -->
    <div class="fixed bottom-6 left-6 z-50 space-y-3 max-w-sm w-full pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div 
                class="pointer-events-auto flex items-center justify-between bg-white border rounded-xl p-3.5 shadow-lg select-none transition-all duration-300"
                :class="[
                    toast.type === 'confirm' ? 'border-amber-200 bg-amber-50/10' : 'border-slate-100',
                    toast.type === 'success' ? 'border-emerald-100 bg-emerald-50/10' : '',
                    toast.type === 'info' ? 'border-blue-100 bg-blue-50/10' : ''
                ]"
                x-transition
            >
                <div class="flex items-start gap-2.5">
                    <!-- Icon based on type -->
                    <div class="mt-0.5">
                        <template x-if="toast.type === 'success'">
                            <i class="fa fa-check-circle text-emerald-500 text-sm"></i>
                        </template>
                        <template x-if="toast.type === 'info'">
                            <i class="fa fa-info-circle text-blue-500 text-sm"></i>
                        </template>
                        <template x-if="toast.type === 'confirm'">
                            <i class="fa fa-exclamation-triangle text-amber-500 text-sm"></i>
                        </template>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-800" x-text="toast.message"></p>
                    </div>
                </div>

                <!-- Custom buttons for confirm dialog -->
                <template x-if="toast.type === 'confirm'">
                    <div class="flex items-center gap-1.5 ms-4 shrink-0">
                        <button 
                            @click="toast.onCancel()"
                            class="px-2 py-1 bg-white border border-slate-200 hover:bg-slate-50 text-[10px] font-bold text-slate-500 rounded-lg transition-all"
                        >
                            No
                        </button>
                        <button 
                            @click="toast.onConfirm()"
                            class="px-2.5 py-1 bg-rose-500 hover:bg-rose-600 text-[10px] font-bold text-white rounded-lg shadow-sm transition-all"
                        >
                            Yes
                        </button>
                    </div>
                </template>
            </div>
        </template>
    </div>

</div>
@endsection

@push('scripts')
    <!-- Load SortableJS for drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <!-- Load AlpineJS (v3) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        function formBuilder() {
            return {
                title: 'Untitled Form',
                submissionUrl: 'https://example.com/forms/submit',
                fields: [],
                selectedFieldId: null,
                activeTab: 'editor',
                activePaletteTab: 'add',
                mode: 'edit',
                
                // History Stack
                history: [],
                historyIndex: -1,
                
                // Modals & Messages
                isJsonModalOpen: false,
                jsonSchemaOutput: '',
                toasts: [],
                
                // Extra Form settings
                settings: {
                    submitLabel: 'Submit Form',
                    successMessage: 'Thank you! Your submission has been received.',
                    themeColor: 'indigo'
                },
                
                isDraggingOver: false,
                
                init() {
                    // Load saved state from LocalStorage
                    const savedData = localStorage.getItem('laravel_form_builder_state');
                    if (savedData) {
                        try {
                            const parsed = JSON.parse(savedData);
                            this.title = parsed.title || 'Untitled Form';
                            this.submissionUrl = parsed.submissionUrl || 'https://example.com/forms/submit';
                            this.fields = parsed.fields || [];
                            this.settings = parsed.settings || this.settings;
                        } catch (e) {
                            console.error("Failed to restore saved builder state", e);
                        }
                    }
                    
                    // Set up initial history point
                    this.saveHistory();
                    
                    // Launch SortableJS
                    this.$nextTick(() => {
                        this.initDragAndDrop();
                    });
                    
                    // Set up dynamic bindings watchers
                    this.$watch('fields', () => { this.persist(); });
                    this.$watch('title', () => { this.persist(); });
                    this.$watch('submissionUrl', () => { this.persist(); });
                    this.$watch('settings', () => { this.persist(); });
                    
                    // Bind shortcut keys for Undo / Redo
                    window.addEventListener('keydown', (e) => {
                        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'z') {
                            e.preventDefault();
                            this.undo();
                        }
                        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'y') {
                            e.preventDefault();
                            this.redo();
                        }
                    });
                },
                
                persist() {
                    localStorage.setItem('laravel_form_builder_state', JSON.stringify({
                        title: this.title,
                        submissionUrl: this.submissionUrl,
                        fields: this.fields,
                        settings: this.settings
                    }));
                },
                
                saveHistory() {
                    // Truncate stack if user has undone items and then makes a new change
                    if (this.historyIndex < this.history.length - 1) {
                        this.history = this.history.slice(0, this.historyIndex + 1);
                    }
                    
                    const snapshot = JSON.stringify({
                        title: this.title,
                        submissionUrl: this.submissionUrl,
                        fields: this.fields,
                        settings: this.settings
                    });
                    
                    // Only push if there's a difference from last index
                    if (this.history.length > 0 && this.history[this.historyIndex] === snapshot) {
                        return;
                    }
                    
                    this.history.push(snapshot);
                    this.historyIndex = this.history.length - 1;
                },
                
                undo() {
                    if (this.historyIndex > 0) {
                        this.historyIndex--;
                        this.restoreSnapshot(this.history[this.historyIndex]);
                        this.showToast('Undo performed', 'info');
                    } else {
                        this.showToast('Nothing to undo', 'info');
                    }
                },
                
                redo() {
                    if (this.historyIndex < this.history.length - 1) {
                        this.historyIndex++;
                        this.restoreSnapshot(this.history[this.historyIndex]);
                        this.showToast('Redo performed', 'info');
                    } else {
                        this.showToast('Nothing to redo', 'info');
                    }
                },
                
                restoreSnapshot(snapshotStr) {
                    try {
                        const parsed = JSON.parse(snapshotStr);
                        this.title = parsed.title;
                        this.submissionUrl = parsed.submissionUrl;
                        this.fields = parsed.fields;
                        this.settings = parsed.settings;
                        
                        // Clear active options selections if field was deleted
                        if (this.selectedFieldId && !this.fields.find(f => f.id === this.selectedFieldId)) {
                            this.selectedFieldId = null;
                            this.activePaletteTab = 'add';
                        }
                    } catch(err) {
                        console.error('History restoration error', err);
                    }
                },

                
                addOption(field) {
                    if (!field.options) field.options = [];
                    const nextNum = field.options.length + 1;
                    field.options.push(`Option ${nextNum}`);
                    this.saveHistory();
                    this.showToast('Option added', 'success');
                },
                
                removeOption(field, idx) {
                    if (field.options.length > 1) {
                        field.options.splice(idx, 1);
                        this.saveHistory();
                        this.showToast('Option removed', 'info');
                    }
                },
                
                editField(fieldId) {
                    this.selectedFieldId = fieldId;
                    this.activePaletteTab = 'options';
                },
                
                getSelectedField() {
                    return this.fields.find(f => f.id === this.selectedFieldId);
                },
                
                duplicateField(fieldId) {
                    const idx = this.fields.findIndex(f => f.id === fieldId);
                    if (idx !== -1) {
                        const copy = JSON.parse(JSON.stringify(this.fields[idx]));
                        copy.id = 'field_' + Math.random().toString(36).substr(2, 9);
                        copy.label += ' (Copy)';
                        this.fields.splice(idx + 1, 0, copy);
                        this.saveHistory();
                        this.showToast('Field duplicated', 'success');
                    }
                },
                
                deleteField(fieldId) {
                    const target = this.fields.find(f => f.id === fieldId);
                    if (!target) return;
                    
                    this.showConfirmToast(`Delete field "${target.label || target.type}"?`, () => {
                        this.fields = this.fields.filter(f => f.id !== fieldId);
                        if (this.selectedFieldId === fieldId) {
                            this.selectedFieldId = null;
                            this.activePaletteTab = 'add';
                        }
                        this.saveHistory();
                        this.showToast('Field deleted', 'info');
                    });
                },
                
                resetWholeBuilder() {
                    this.showConfirmToast('Reset builder canvas? This deletes all fields.', () => {
                        this.fields = [];
                        this.selectedFieldId = null;
                        this.activePaletteTab = 'add';
                        this.saveHistory();
                        this.persist();
                        this.showToast('Builder reset complete');
                    });
                },
                
                exportSchema() {
                    const schema = {
                        form_title: this.title,
                        form_url: this.submissionUrl,
                        settings: this.settings,
                        fields: this.fields.map(f => {
                            const result = {
                                id: f.id,
                                type: f.type,
                                label: f.label,
                                required: f.required,
                                css_class: f.cssClass,
                                defaultValue: f.defaultValue
                            };
                            if (['text', 'textarea', 'number', 'email', 'phone', 'dropdown', 'state', 'city'].includes(f.type)) {
                                result.placeholder = f.placeholder;
                            }
                            if (['text', 'textarea'].includes(f.type)) {
                                result.min = f.min;
                                result.max = f.max;
                            }
                            if (['dropdown', 'radio', 'checkbox'].includes(f.type)) {
                                result.options = f.options;
                            }
                            return result;
                        })
                    };
                    
                    this.jsonSchemaOutput = JSON.stringify(schema, null, 2);
                    this.isJsonModalOpen = true;
                    console.log('Serialized JSON Schema:', schema);
                },
                
                copyToClipboard() {
                    navigator.clipboard.writeText(this.jsonSchemaOutput)
                        .then(() => {
                            this.showToast('JSON Copied to Clipboard!', 'success');
                        })
                        .catch(err => {
                            console.error('Copy failed', err);
                        });
                },

                
                showToast(message, type = 'success') {
                    const id = Date.now();
                    this.toasts.push({ id, message, type });
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 3000);
                },
                
                showConfirmToast(message, callback) {
                    const id = Date.now();
                    this.toasts.push({ 
                        id, 
                        message, 
                        type: 'confirm', 
                        onConfirm: () => {
                            callback();
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        },
                        onCancel: () => {
                            this.toasts = this.toasts.filter(t => t.id !== id);
                        }
                    });
                },
                
                initDragAndDrop() {
                    const paletteGrids = document.querySelectorAll('.palette-grid');
                    const canvasEl = document.getElementById('canvas-dropzone');
                    if (!canvasEl) return;
                    
                    const self = this;
                    
                    // Initialize Sortable on all category grids in the palette
                    paletteGrids.forEach(grid => {
                        new Sortable(grid, {
                            group: {
                                name: 'form-elements',
                                pull: 'clone',
                                put: false
                            },
                            sort: false,
                            animation: 180,
                            onEnd: () => {
                                self.isDraggingOver = false;
                            }
                        });
                    });
                    
                    // Canvas Sortable initialization (drop target & sorting)
                    new Sortable(canvasEl, {
                        group: 'form-elements',
                        animation: 180,
                        handle: '.drag-handle',
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        
                        onDragOver: (evt) => {
                            self.isDraggingOver = true;
                        },
                        
                        onDragLeave: (evt) => {
                            self.isDraggingOver = false;
                        },
                        
                        onAdd: (evt) => {
                            self.isDraggingOver = false;
                            const type = evt.item.getAttribute('data-type');
                            
                            // Remove Sortable's DOM clone, Alpine will render it
                            if (evt.item.parentNode) {
                                evt.item.parentNode.removeChild(evt.item);
                            }
                            
                            const newFieldObj = self.createFieldObj(type);
                            self.fields.splice(evt.newIndex, 0, newFieldObj);
                            
                            self.saveHistory();
                            
                            // Edit the newly added field
                            self.editField(newFieldObj.id);
                            self.showToast(`Placed ${newFieldObj.label} in workspace`, 'success');
                        },
                        
                        onUpdate: (evt) => {
                            const moved = self.fields.splice(evt.oldIndex, 1)[0];
                            self.fields.splice(evt.newIndex, 0, moved);
                            self.saveHistory();
                            self.showToast('Reordered field position', 'success');
                        }
                    });
                },
                
                createFieldObj(type) {
                    const id = 'field_' + Math.random().toString(36).substr(2, 9);
                    const label = this.getFieldDefaultLabel(type);
                    const placeholder = this.getFieldDefaultPlaceholder(type);
                    
                    const fObj = {
                        id,
                        type,
                        label,
                        placeholder,
                        required: false,
                        cssClass: 'col-span-12',
                        defaultValue: '',
                        min: null,
                        max: null,
                        options: []
                    };
                    
                    if (['dropdown', 'radio', 'checkbox'].includes(type)) {
                        fObj.options = ['Option 1', 'Option 2', 'Option 3'];
                    }
                    
                    if (type === 'state_city') {
                        fObj.stateValue = '';
                        fObj.cityValue = '';
                    }
                    
                    return fObj;
                },
                
                getFieldDefaultLabel(type) {
                    const labels = {
                        text: 'Text Input',
                        textarea: 'Text Area',
                        number: 'Number Input',
                        email: 'Email Address',
                        phone: 'Phone Number',
                        dropdown: 'Dropdown Selection',
                        radio: 'Radio Selection Group',
                        checkbox: 'Checkboxes Options',
                        date: 'Select Date',
                        file: 'Attach File',
                        title: 'Section Title Heading',
                        description: 'Instructions or helpful descriptions...',
                        newline: 'Layout Divider',
                        pagebreak: 'Page Splitter',
                        hidden: 'Hidden Field Parameter',
                        state: 'US State Selection',
                        city: 'City Name',
                        state_city: 'Combined Location'
                    };
                    return labels[type] || 'Form Input Field';
                },
                
                getFieldDefaultPlaceholder(type) {
                    const place = {
                        text: 'Enter single-line text...',
                        textarea: 'Type detailed details here...',
                        number: 'Enter numeric value...',
                        email: 'username@domain.com',
                        phone: '+1 (123) 456-7890',
                        dropdown: 'Select option...',
                        state: 'Select US State...',
                        city: 'e.g. San Francisco'
                    };
                    return place[type] || '';
                }
            };
        }
    </script>
@endpush