# DiabTrack - Sistema de Gestión de Salud para la Diabetes

[![Production](https://img.shields.io/badge/Production-diabtrack.app-blue?style=flat-square)](https://diabtrack.app)
[![Framework](https://img.shields.io/badge/Framework-Laravel%2013-red?style=flat-square)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square)](https://php.net)
[![Infrastructure](https://img.shields.io/badge/Infrastructure-Docker-2496ED?style=flat-square)](https://docker.com)

DiabTrack es una plataforma profesional diseñada para el monitoreo integral de la diabetes. Permite a pacientes, médicos y cuidadores gestionar indicadores glucémicos, nutrición, actividad física y signos vitales en un entorno seguro y centralizado.

---

## 🏗️ Arquitectura del Proyecto

DiabTrack utiliza una **Arquitectura Monolítica** basada en el patrón de diseño **MVC (Modelo-Vista-Controlador)**. Esta estructura permite un desarrollo cohesivo donde el frontend y el backend coexisten para facilitar el despliegue y la consistencia de los datos.

### 🧩 El Patrón MVC en DiabTrack:

*   **Modelos (`app/Models/`):** Gestionan la lógica de datos y las reglas de negocio. Representan las entidades del sistema como `User`, `PatientProfile`, `VitalSign`, `NutritionLog`, etc.
*   **Vistas (`resources/views/`):** La interfaz de usuario construida con el motor de plantillas **Blade**. Está organizada por módulos (Admin, Paciente, Médico, Cuidador) para ofrecer una experiencia personalizada según el rol.
*   **Controladores (`app/Http/Controllers/`):** Actúan como intermediarios, procesando las solicitudes del usuario, interactuando con los modelos y devolviendo las vistas correspondientes.

### 🛠️ Capas Adicionales:
*   **Service Layer (`app/Services/`):** Implementamos servicios como `DashboardMetricsService` para manejar cálculos complejos de salud fuera de los controladores, siguiendo el principio de responsabilidad única.
*   **Middleware (`app/Http/Middleware/`):** Capas de seguridad y control de flujo que gestionan el acceso por roles y aseguran que el proceso de *onboarding* se complete.

---

## 🚀 Stack Tecnológico

### Backend
*   **Lenguaje:** PHP 8.3 con tipado estricto.
*   **Framework:** [Laravel 13](https://laravel.com).
*   **Autenticación:** Laravel Breeze y Socialite (Google OAuth).
*   **Comunicaciones:** Resend API para correos transaccionales.

### Frontend
*   **Estilos:** [Tailwind CSS](https://tailwindcss.com) (Moderno, responsivo y optimizado).
*   **Bundler:** [Vite](https://vitejs.dev) para una compilación ultra rápida de assets.
*   **Plantillas:** Blade (Laravel native).
*   **Gráficos:** Chart.js para visualización de tendencias glucémicas.

### Infraestructura y Base de Datos
*   **Contenedores:** [Docker](https://www.docker.com) (Configuraciones para Desarrollo y Producción).
*   **Base de Datos:** MySQL 8.0 optimizada con índices para series temporales de salud.

---

## 🔑 Funcionalidades Principales

*   **Multi-Rol:** Interfaces específicas para Pacientes, Médicos, Cuidadores y Administradores.
*   **Seguimiento Integral:** Registro de glucosa, presión arterial, peso, estrés, nutrición y actividad física.
*   **Análisis de Datos:** Cálculo automático de promedios, estimación de A1c y cumplimiento de metas.
*   **Onboarding Guiado:** Proceso paso a paso para configurar perfiles de salud detallados.

---

## 🛠️ Instalación y Configuración

### Requisitos
*   Docker y Docker Compose instalados.

### Pasos
1. **Clonar el repo:**
   ```bash
   git clone https://github.com/tu-usuario/diabtracktest.git
   cd diabtracktest
   ```

2. **Configurar el entorno:**
   ```bash
   cp .env.example .env
   ```

3. **Levantar el proyecto con Docker:**
   ```bash
   docker-compose up -d
   ```

4. **Instalación automática:**
   ```bash
   # Este comando ejecuta composer, npm, migraciones y generación de llaves
   docker-compose exec app php artisan setup
   ```

---

## 🧪 Pruebas
Ejecuta la suite de pruebas para asegurar la integridad del sistema:
```bash
docker-compose exec app php artisan test
```

---

## 🛡️ Seguridad
*   Protección contra CSRF, XSS e inyección SQL nativa de Laravel.
*   Gestión de permisos basada en roles (RBAC).
*   Encriptación de datos sensibles.

---
© 2026 DiabTrack App. Desarrollo profesional para el control de la salud.
