# DiabTrack Health Management System

[![Production](https://img.shields.io/badge/Production-diabtrack.app-blue?style=flat-square)](https://diabtrack.app)
[![Framework](https://img.shields.io/badge/Framework-Laravel%2013-red?style=flat-square)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square)](https://php.net)
[![Infrastructure](https://img.shields.io/badge/Infrastructure-Docker-2496ED?style=flat-square)](https://docker.com)

DiabTrack es una plataforma de grado profesional diseñada para la gestión clínica y personal de la diabetes. El sistema integra el seguimiento de indicadores glucémicos, métricas nutricionales y actividad física en un entorno seguro y escalable.

---

## 1. Descripción General

La plataforma ofrece una solución robusta para el monitoreo de pacientes, permitiendo la toma de decisiones basada en datos. Utiliza algoritmos de cálculo para proyectar tendencias de salud y proporciona una interfaz analítica para el control diario de la patología.

---

## 2. Stack Tecnológico

### Backend y Lógica de Negocio
*   **Lenguaje:** PHP 8.3 con tipado estricto.
*   **Framework:** Laravel 13.
*   **Servicios Externos:** 
    *   Resend API (Comunicaciones Transaccionales).
    *   Google Socialite (OAuth 2.0).
*   **Arquitectura:** Service Layer Pattern para el procesamiento de métricas.

### Frontend
*   **Motor de Plantillas:** Blade.
*   **Asset Bundler:** Vite.
*   **Estilos:** Bootstrap 5.3 y Design System propietario basado en CSS3 Moderno.
*   **Visualización:** Chart.js para análisis de tendencias.

### Infraestructura
*   **Contenedores:** Docker y Docker Compose.
*   **Base de Datos:** MySQL 8.0 con optimización de índices para series temporales de salud.

---

## 3. Arquitectura y Funcionalidades Clave

### Dashboard Analítico
Procesamiento centralizado de datos a través de `DashboardMetricsService`, que calcula:
*   Promedios de glucosa y estimación de Hemoglobina Glicosilada (A1c).
*   Cumplimiento de metas nutricionales y físicas.
*   Visualización de series temporales de signos vitales.

### Gestión de Identidad
*   Sistema de autenticación híbrido (Tradicional + Google OAuth).
*   Control de acceso basado en roles (RBAC).

### Optimización SEO y Producción
*   Configuración nativa para el dominio **diabtrack.app**.
*   Generación automática de sitemap y cumplimiento de protocolos `robots.txt`.
*   Implementación de meta-etiquetas Open Graph y Twitter Cards.

---

## 4. Instalación y Despliegue

### Requisitos Previos
*   Docker Engine 24.0+
*   Docker Compose 2.0+

### Pasos de Instalación
1.  Clonar el repositorio:
    ```bash
    git clone https://github.com/tu-usuario/diabtrack.git
    ```
2.  Configurar variables de entorno:
    ```bash
    cp .env.example .env
    ```
3.  Desplegar contenedores:
    ```bash
    docker-compose up -d
    ```
4.  Inicializar sistema:
    ```bash
    docker-compose exec app php artisan setup-project
    ```

---

## 5. Pruebas y Calidad
Para ejecutar la suite de pruebas automatizadas:
```bash
docker-compose exec app php artisan test
```

---

## 6. Seguridad
La plataforma implementa múltiples capas de seguridad:
*   Protección contra ataques CSRF y XSS.
*   Encriptación de datos sensibles en tránsito (SSL/TLS).
*   Sanitización estricta de entradas de usuario.

---

## 7. Contacto y Soporte
*   **Dominio Oficial:** [https://diabtrack.app](https://diabtrack.app)
*   **Mantenimiento:** Equipo de Desarrollo DiabTrack.

---
© 2026 DiabTrack App. Todos los derechos reservados.
