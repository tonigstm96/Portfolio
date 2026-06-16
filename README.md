# Entorno de Desarrollo Local - Portfolio

Este repositorio contiene un entorno de desarrollo configurado con **npm** y **Docker** para levantar un servidor de PHP con recarga automática en el navegador sin necesidad de plugins en el editor.

---

## Requisitos Previos

Para ejecutar el entorno, asegúrate de tener instalado en tu máquina:
1. **Docker**: Para ejecutar el contenedor de PHP.
2. **Node.js y npm**: Para ejecutar BrowserSync y gestionar los comandos.

---

## Comandos Disponibles

Todos los comandos se ejecutan desde la raíz del proyecto utilizando `npm`:

| Comando | Acción | Puerto en Local |
| :--- | :--- | :--- |
| **`npm start`** | Lanza el servidor PHP (Docker) y BrowserSync en paralelo (Recomendado). | `http://localhost:3000` |
| **`npm run dev`** | Alias de `npm start`. Lanza todo el entorno. | `http://localhost:3000` |
| **`npm run php`** | Levanta únicamente el contenedor de Docker con el servidor PHP integrado. | `http://127.0.0.1:8000` |
| **`npm run sync`** | Levanta únicamente BrowserSync (requiere que el servidor PHP esté activo). | `http://localhost:3000` |

---

## Guía de Uso Rápido

1. **Iniciar el Servidor:**
   Abre una terminal en la raíz del proyecto y ejecuta:
   ```bash
   npm start
   ```
   *Nota: La primera vez puede tardar unos segundos mientras Docker descarga la imagen oficial de `php:8.2-cli`.*

2. **Acceso Web:**
   El navegador se abrirá automáticamente en `http://localhost:3000`. 

3. **Desarrollo en Caliente:**
   Modifica cualquier archivo (`.html`, `.php`, `.css` o `.js`). Al guardar, el navegador se refrescará automáticamente sin necesidad de F5.

4. **Detener el Servidor:**
   Pulsa `Ctrl+C` en la terminal. El contenedor de Docker se detendrá y se destruirá automáticamente (`--rm`), liberando los recursos de tu sistema de forma limpia.

---

## Detalles Técnicos

- **Contenedor PHP CLI:** Corre en base a la imagen `php:8.2-cli` mapeando la carpeta actual al directorio `/app` del contenedor y levantando el servidor integrado de PHP mediante `php -S 0.0.0.0:8000`.
- **BrowserSync Proxy:** Escucha los cambios del sistema de archivos y hace de pasarela (proxy) hacia el puerto `8000` expuesto por Docker, inyectando el script necesario para la recarga en caliente en el puerto `3000`.
- **Concurrently:** Coordina el ciclo de vida de ambos procesos para que arranquen y se detengan juntos de forma limpia.
