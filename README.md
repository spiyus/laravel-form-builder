# Drag-and-Drop Form Builder UI - Laravel Assignment

A modern, highly polished, and fully functional drag-and-drop Form Builder built directly inside the provided Laravel project. Users can visually construct HTML forms by dragging inputs and layout elements from a sidebar panel into a central drop canvas. Once placed, fields can be configured (label, placeholder, min/max limits, options, CSS class, default values), duplicated, reordered, and deleted.

---

## 🚀 Setup & Execution

Since the implementation leverages standard, high-performance CDNs (Tailwind CSS, Alpine.js, SortableJS), **no npm build steps are required** on your local machine.

1. **Install Composer dependencies:**
   ```bash
   composer upgrade
   ```

2. **Start the Laravel local server:**
   ```bash
   php artisan serve
   ```

3. **Open the application in your browser:**
   Go to [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🎨 Feature Summary & Highlights

### 1. Two-Column Layout (Form Editor)
* **Header Bar**: Rich dark-indigo title bar featuring live form title inputs (maximum 200 characters) with a live letter counter, a POST submission target indicator, a functional settings panel toggle, and a Live Form Preview toggler.
* **Left Column (Drop Canvas)**: Dotted drop zone featuring active drag-over visual borders. Renders elements as cards. Each card contains a pill of action controls: Drag Handle, Edit, Duplicate, and Delete.
* **Right Column (Sidebar Panel)**:
  * **Add Fields**: Drag-palette categorizing standard inputs, select menus, location helpers, and structural layout boundaries with clean SVG icons.
  * **Field Options**: Configures the selected canvas element reactively. Updates elements live as you type.

### 2. Supported Field Types (18 Total)
* **Standard Inputs**: Text Input, Text Area, Number Input, Email Input, Phone Input, Date Picker, File Upload.
* **Choice Selections**: Dropdown Select, Radio Buttons, Checkboxes.
* **Location Helpers**: State, City, State & City Combined.
* **Structure & Layout**: Title Header, Description text, New Line separator, Page Break dotted separator, Hidden Field (displays badge in editor, invisible in preview).

### 3. Placed Field Interactions
* **Drag-to-Reorder**: Move fields up and down the canvas via drag handles.
* **Live Configuration (Edit)**: Swaps right panel to the options sheet. Changes update canvas elements instantly.
* **Duplicate**: Clones a field directly below the original with settings intact.
* **Delete**: Removes items with an inline double-check confirmation toast.

### 4. Optional / Bonus Features Included
* **Undo / Redo History**: Complete state history mapping. Step backwards/forwards via top-right arrows or keyboard shortcuts (`Ctrl+Z` / `Ctrl+Y` or `Cmd+Z` / `Cmd+Y`).
* **Interactive Preview Mode**: Toggling switches the canvas to a live HTML form. Fields become active and support form validation rules before triggering a mock JSON submit popup.
* **LocalStorage Persistence**: Form designs survive page reloads automatically.
* **Drag-over Visual Feedback**: Drops light-blue highlights and borders on active drag.
* **State-to-City Chaining**: Selecting States in the combined location helper automatically filters the respective Cities in the adjacent dropdown.

---

## 🛠️ Design Decisions & Tech Rationale

### 1. Drag & Drop Choice: `SortableJS`
We selected **SortableJS** for the drag-and-drop behavior.
* **Rationale**: 
  * Lightweight (no heavy layout engine required).
  * Highly performant with sub-millisecond sorting animations.
  * Frame-rate friendly layout transitions (`animation: 180`).
  * Easily integrates with Alpine.js by capturing Sortable triggers (`onAdd`, `onUpdate`) and reflecting changes into the Alpine state arrays.

### 2. Reactivity: `Alpine.js` (v3)
* **Rationale**:
  * Designed to be directly declared in the DOM alongside server-side HTML/Blade, unlike Vue/React which require full virtual DOM mounts and build/compile processes.
  * Facilitates two-way binding (`x-model`), allowing the Options Panel to instantly update elements on the canvas as you edit.

### 3. Layout and Styling: `Tailwind CSS`
* **Rationale**:
  * Loaded Tailwind via CDN with `corePlugins: { preflight: false }`. Disabling preflight resets ensures Tailwind utility classes work without breaking the project's existing Bootstrap 5 layout (navigation menu and sidebar container).

---

## 📝 Sample JSON Schema Output
This is the structured JSON output produced by the Form Builder when clicking the **Next / Export Schema** button:

```json
{
  "form_title": "Job Application Form",
  "form_url": "https://example.com/forms/submit",
  "settings": {
    "submitLabel": "Submit Application",
    "successMessage": "Thank you! Your job application has been successfully submitted.",
    "themeColor": "indigo"
  },
  "fields": [
    {
      "id": "field_x3s8a1d9g",
      "type": "title",
      "label": "Candidate Information",
      "required": false,
      "css_class": "col-span-12",
      "defaultValue": ""
    },
    {
      "id": "field_j9d2a3s4b",
      "type": "text",
      "label": "Full Name",
      "required": true,
      "css_class": "col-span-6",
      "defaultValue": "",
      "placeholder": "e.g. Jane Doe",
      "min": 2,
      "max": 100
    },
    {
      "id": "field_e5a2b8n1m",
      "type": "email",
      "label": "Email Address",
      "required": true,
      "css_class": "col-span-6",
      "defaultValue": "",
      "placeholder": "jane.doe@example.com"
    },
    {
      "id": "field_p4c8a2k9q",
      "type": "phone",
      "label": "Phone Number",
      "required": true,
      "css_class": "col-span-6",
      "defaultValue": "",
      "placeholder": "+1 (555) 019-2834"
    },
    {
      "id": "field_d9k3j4a2c",
      "type": "dropdown",
      "label": "Position Applied For",
      "required": true,
      "css_class": "col-span-6",
      "defaultValue": "Software Engineer",
      "placeholder": "Select a role...",
      "options": [
        "Software Engineer",
        "Product Manager",
        "UI/UX Designer",
        "QA Engineer"
      ]
    },
    {
      "id": "field_s9x2n5v1w",
      "type": "state_city",
      "label": "Location Preference",
      "required": false,
      "css_class": "col-span-12",
      "defaultValue": ""
    },
    {
      "id": "field_f4c9x2v8l",
      "type": "file",
      "label": "Upload Resume/CV",
      "required": true,
      "css_class": "col-span-12",
      "defaultValue": ""
    },
    {
      "id": "field_n7d2j3a9m",
      "type": "newline",
      "label": "Layout Divider",
      "required": false,
      "css_class": "col-span-12",
      "defaultValue": ""
    },
    {
      "id": "field_r4a3s2d1f",
      "type": "checkbox",
      "label": "Preferred Work Mode",
      "required": false,
      "css_class": "col-span-12",
      "defaultValue": "",
      "options": [
        "On-site",
        "Hybrid",
        "Fully Remote"
      ]
    },
    {
      "id": "field_t7y3u4i5o",
      "type": "textarea",
      "label": "Cover Letter / Notes",
      "required": false,
      "css_class": "col-span-12",
      "defaultValue": "",
      "placeholder": "Tell us why you are a great fit...",
      "min": 10,
      "max": 1000
    }
  ]
}
```
# laravel-form-builder
