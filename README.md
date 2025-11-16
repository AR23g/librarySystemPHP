# Sistema de Gestión de Biblioteca - LibrarySystemPHP

Sistema web desarrollado en PHP con patrón MVC para la administración de una biblioteca pequeña.

## Características

- **Autenticación de Usuarios**: Registro y login seguro
- **CRUD de Libros**: Crear, leer, actualizar y eliminar libros
- **CRUD de Autores**: Gestión completa de autores
- **Gestión de Préstamos**: Registrar y devolver libros
- **Base de Datos Relacional**: Normalizada en 3FN
- **Interfaz Simple**: Fácil de usar para usuarios básicos

## Requisitos

- PHP 7.4+
- SQLite3
- Servidor web

## Instalación Rápida

1. Descarga el proyecto
2. Coloca en la carpeta web de tu servidor
3. Crea una carpeta llamada 'database' en la raíz del proyecto
4. Accede a http://localhost/librarySystemPHP/index.php

## Credenciales de Prueba

- **Usuario:** admin
- **Contraseña:** 1234

Se crea automáticamente la primera vez que ejecutas la aplicación.

## Estructura MVC

\`\`\`
librarySystemPHP/
├── index.php           # Punto de entrada
├── README.md           # Documentación completa del proyecto
├── app/
│   ├── Router.php      # Enrutador
│   ├── models/         # Modelos de datos
│   ├── controllers/    # Controladores
│   └── views/          # Vistas HTML
├── config/
│   └── database.php    # Configuración DB
├── database/           # Archivos SQLite
└── public/
\`\`\`

## Requerimientos Funcionales - Análisis

### Autenticación de Usuarios

#### Inicio de Sesión (Login)
- **RF-AU-001:** El sistema debe proporcionar un formulario de inicio de sesión con campos para usuario y contraseña.
- **RF-AU-002:** El sistema debe validar que los campos de usuario y contraseña no estén vacíos antes de procesar el login.
- **RF-AU-003:** El sistema debe verificar las credenciales del usuario comparándolas con la base de datos utilizando hash de contraseñas seguras (bcrypt).
- **RF-AU-004:** En caso de credenciales inválidas, el sistema debe mostrar un mensaje de error y permitir reintentos, con un límite de 5 intentos fallidos por sesión.
- **RF-AU-005:** Al iniciar sesión exitosamente, el sistema debe crear una sesión segura y redirigir al usuario al dashboard.
- **RF-AU-006:** El sistema debe permitir cierre de sesión que destruya la sesión y redirigir al formulario de login.

#### Registro de Usuarios (Register)
- **RF-AU-101:** El sistema debe proporcionar un formulario de registro con campos para nombre de usuario, correo electrónico, contraseña y confirmación de contraseña.
- **RF-AU-102:** El sistema debe validar que todos los campos obligatorios estén completos antes de procesar el registro.
- **RF-AU-103:** El sistema debe validar el formato del correo electrónico utilizando expresiones regulares.
- **RF-AU-104:** El sistema debe imponer requisitos de fortaleza de contraseña: mínimo 8 caracteres, al menos una mayúscula y un número.
- **RF-AU-105:** El sistema debe verificar que la contraseña y su confirmación coincidan exactamente.
- **RF-AU-106:** El sistema debe verificar la unicidad del nombre de usuario y correo electrónico en la base de datos.
- **RF-AU-107:** Al registro exitoso, el sistema debe almacenar la contraseña hasheada, mostrar mensaje de éxito y redirigir al formulario de login.
- **RF-AU-108:** El sistema debe incluir validación del lado del servidor para prevenir ataques de inyección y asegurar la integridad de los datos.

### Estructuración de la Lógica de Negocio

#### Arquitectura General
El sistema sigue el patrón Modelo-Vista-Controlador (MVC) para separar la lógica de negocio de la presentación y el control de flujo.

#### Controladores
- **AuthController:** Gestiona autenticación y autorización. Incluye lógica para validar intentos de login, manejar registros con validaciones críticas de seguridad, y asegurar el cierre de sesiones.
- **BookController:** Maneja operaciones CRUD de libros con autorización previa.
- **AuthorController:** Gestiona CRUD de autores con validaciones de unicidad.
- **LoanController:** Controla préstamos, devoluciones y actualizaciones de inventario de libros.
- **DashboardController:** Presenta la vista principal después de autenticación.

#### Modelos
- **User:** Acceso a datos de usuarios con métodos para creación, verificación de contraseñas y consultas seguras.
- **Book:** Operaciones de libros con validaciones de inventario y referencias a autores.
- **Author:** Gestión de autores con unicidad del nombre.
- **Loan:** Manejo de préstamos con lógica de fechas y estado.

#### Validaciones de Negocio
- Unicidad de datos críticos (usernames, emails, ISBN, nombres de autores).
- Integridad referencial mediante claves foráneas.
- Control de inventario de libros (copias disponibles).
- Sesiones seguras con verificación de autenticación en rutas protegidas.
- Hashing de contraseñas con algoritmos seguros.

#### Flujos de Trabajo
1. **Autenticación:** Usuario accede a login/register -> Controlador procesa -> Modelo valida con DB -> Vista redirige según resultado.
2. **Gestión de Recursos:** Usuario autenticado -> Controlador verifica autorización -> Modelo ejecuta operación -> Vista muestra resultado.
3. **Préstamos:** Crear préstamo -> Validar disponibilidad -> Actualizar inventario -> Mostrar confirmación.

## Estructura de la Base de Datos - Normalización

La base de datos está normalizada a la Tercera Forma Normal (3FN) para asegurar escalabilidad y mantenibilidad.

### Forma Normal 1 (1FN)
- Todos los valores son atómicos (sin listas o estructuras compuestas en una celda).
- No hay grupos repetidos.

### Forma Normal 2 (2FN)
- Cumple 1FN.
- No hay dependencias parciales: Todos los atributos no clave dependen completamente de la clave primaria.

### Forma Normal 3 (3FN)
- Cumple 2FN.
- No hay dependencias transitivas: Los atributos no clave no dependen de otros atributos no clave.

### Esquema de Tablas
-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    userId INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de autores
CREATE TABLE IF NOT EXISTS authors (
    authorId INTEGER PRIMARY KEY AUTOINCREMENT,
    authorName TEXT NOT NULL UNIQUE,
    biography TEXT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de libros
CREATE TABLE IF NOT EXISTS books (
    bookId INTEGER PRIMARY KEY AUTOINCREMENT,
    bookTitle TEXT NOT NULL,
    authorId INTEGER NOT NULL,
    isbn TEXT UNIQUE NOT NULL,
    publicationYear INTEGER,
    totalCopies INTEGER DEFAULT 1,
    availableCopies INTEGER DEFAULT 1,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (authorId) REFERENCES authors(authorId) ON DELETE CASCADE
);

-- Tabla de préstamos
CREATE TABLE IF NOT EXISTS loans (
    loanId INTEGER PRIMARY KEY AUTOINCREMENT,
    userId INTEGER NOT NULL,
    bookId INTEGER NOT NULL,
    loanDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    returnDate DATETIME,
    dueDate DATETIME,
    status TEXT DEFAULT 'active',
    FOREIGN KEY (userId) REFERENCES users(userId) ON DELETE CASCADE,
    FOREIGN KEY (bookId) REFERENCES books(bookId) ON DELETE CASCADE
);

-- Usuario administrador por defecto
INSERT INTO users (username, email, password)
SELECT 'admin', 'admin@biblioteca.local', '<HASHED_PASSWORD>'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'admin');

## Funcionalidades del Sistema

1. **AUTENTICACIÓN:**
   - Registro de nuevos usuarios con validación de email y fortaleza de contraseña
   - Login seguro con contraseñas hasheadas y límite de intentos fallidos

2. **GESTIÓN DE LIBROS:**
   - Crear, leer, actualizar, eliminar libros
   - Associar libros a autores existentes
   - Controlar disponibilidad de copias (totales y disponibles)

3. **GESTIÓN DE AUTORES:**
   - Crear, leer, actualizar, eliminar autores
   - Agregar biografía a autores (opcional)

4. **GESTIÓN DE PRÉSTAMOS:**
   - Registrar nuevos préstamos (validando disponibilidad)
   - Devolver libros prestados
   - Visualizar historial de préstamos activos e inactivos
   - Fecha de vencimiento automática (14 días desde préstamo)

## Consideraciones de Seguridad

1. **Contraseñas:** Hasheadas con `PASSWORD_DEFAULT` de PHP (algoritmo actualizado).
2. **Sesiones:** Validadas en cada controlador para acceso autorizado.
3. **Validación de Inputs:** Todos los inputs se validan en servidor para prevenir ataques.
4. **Prepared Statements:** Usados en todas las consultas para evitar inyección SQL.
5. **Límite de Intentos:** Máximo 5 intentos de login fallidos por sesión.
6. **Unicidad de Datos:** Restricciones en usernames, emails, ISBN y nombres de autores.

## Solución de Problemas

1. **Error "database is locked":**
   - Cierra todas las conexiones a la base de datos
   - Elimina la carpeta 'database' y vuelve a crear ejecutando nuevamente

2. **Página en blanco:**
   - Verifica que la carpeta 'database' tenga permisos de escritura
   - Revisa los logs de error de PHP

3. **No puedo iniciar sesión:**
   - Verifica credenciales (admin/1234 por defecto)
   - Limpia cookies y caché del navegador
   - Verifica extensiones PHP activas (PDO, SQLite)

## Documentación del Código

Todas las clases, métodos y propiedades están documentadas usando el estándar PHPDoc:
- Comentarios `/** */` para clases y métodos
- Descripción de parámetros (@param)
- Tipos de datos y valores de retorno (@return)
- Propósito y conocimiento adicional

## Información del Proyecto

- **Autor:** Albert Rivas
- **Fecha:** Noviembre 2025
- **Versión:** 1.0
