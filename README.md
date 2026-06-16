# Entorno de Desarrollo Local - Portafolio (1º DAW)

Este repositorio contiene un entorno de desarrollo moderno configurado con **npm** y **Docker** para levantar un servidor de PHP local con recarga automática en el navegador (hot reload) y permitir la **compilación estática** de PHP a HTML para desplegar en **GitHub Pages**.

---

## 🚀 Requisitos Previos

Para ejecutar el entorno, asegúrate de tener instalado en tu máquina:
1. **Docker**: Para ejecutar el contenedor de PHP.
2. **Node.js y npm**: Para ejecutar BrowserSync y gestionar los comandos.

---

## 🛠️ Comandos Disponibles

Todos los comandos se ejecutan desde la raíz del proyecto utilizando `npm`:

### Desarrollo Local
| Comando | Acción | Puerto en Local |
| :--- | :--- | :--- |
| **`npm start`** o **`npm run dev`** | Lanza el servidor PHP (Docker) y BrowserSync en paralelo (Recomendado). | `http://localhost:3000` |
| **`npm run php`** | Levanta únicamente el contenedor de Docker con el servidor PHP integrado. | `http://127.0.0.1:8000` |
| **`npm run sync`** | Levanta únicamente BrowserSync (requiere que el servidor PHP esté activo). | `http://localhost:3000` |

### Compilación y Despliegue
| Comando | Acción |
| :--- | :--- |
| **`npm run build`** | Compila los archivos `.php` a `.html` estáticos en la carpeta `dist/` usando Docker. |
| **`npm run deploy`** | Despliega la carpeta `dist/` directamente a tu rama `gh-pages` de GitHub Pages. |
| **`npm run publish`** | Compila y despliega en un solo paso (`build` + `deploy`). |

---

## 💻 Guía de Desarrollo Local

1. **Iniciar el Servidor:**
   Ejecuta `npm start` en tu terminal. El navegador se abrirá automáticamente en `http://localhost:3000`.
2. **Desarrollo en Caliente:**
   Modifica cualquier archivo. Al guardar, el navegador se refrescará automáticamente sin necesidad de F5.
3. **Estructuración con PHP:**
   - Puedes usar archivos `.php` en la raíz (ej. `index.php`, `contacto.php`).
   - **Plantillas/Fragmentos:** Si creas partes reutilizables (como el header o footer), nómbralos con un guion bajo inicial (ej: `_header.php`, `_footer.php`). Así, el compilador sabrá que son plantillas y **no** generará una página HTML individual para ellas.
4. **Detener el Servidor:**
   Pulsa `Ctrl+C` en la terminal. El contenedor de Docker se detendrá y destruirá automáticamente (`--rm`), liberando los recursos.

---

## 🌐 Compilación y Despliegue a GitHub Pages

Cuando tu web esté lista para publicarse en GitHub Pages:

1. **Compilar y Desplegar:**
   Ejecuta el siguiente comando en la raíz:
   ```bash
   npm run publish
   ```
2. **¿Qué sucede internamente?**
   - Se crea una carpeta `dist/`.
   - Se ejecutan y compilan los archivos `.php` a `.html` (los enlaces a archivos `.php` se reescriben automáticamente a `.html`, ej: `contacto.php` pasa a ser `contacto.html` en el código compilado).
   - Se copian las carpetas estáticas (`style/`, `styles/`, `media/`, `views/`).
   - El paquete `gh-pages` crea o actualiza la rama `gh-pages` en tu repositorio de GitHub y sube los contenidos de la carpeta `dist/`.

---

## 🔍 Detalles Técnicos

- **Contenedor PHP CLI:** Corre en base a la imagen `php:8.2-cli` mapeando la carpeta actual al directorio `/app` del contenedor y levantando el servidor integrado de PHP mediante `php -S 0.0.0.0:8000`.
- **BrowserSync Proxy:** Escucha los cambios del sistema de archivos y hace de pasarela (proxy) hacia el puerto `8000` expuesto por Docker, inyectando el script necesario para la recarga en caliente en el puerto `3000`.
- **Compilador (`scripts/compile.php`):** Script encargado de automatizar la generación estática y limpiar las rutas de enlace internas para asegurar que el portafolio funcione 100% estático en producción.
