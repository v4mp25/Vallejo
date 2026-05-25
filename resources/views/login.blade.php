<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intranet - Estudiantes y Docentes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white flex items-center justify-center min-h-screen font-sans text-gray-800">
    <div class="w-full max-w-sm p-6">
        
        <div class="flex justify-center mb-6">
            <div class="w-28 h-28 bg-gray-200 rounded-full flex items-center justify-center text-xs text-gray-500 text-center border-2 border-gray-300">
                Logo <br> Colegio
            </div>
        </div>

        <h2 class="text-center text-lg font-semibold text-[#1a2b4c] mb-6">INTRANET DE ESTUDIANTES</h2>

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-5 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/iniciar-sesion" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-medium mb-1">Código de Estudiante</label>
                <input type="text" name="codigo_usuario" placeholder="Ej: 2025110212" required 
                       class="w-full border border-gray-300 rounded px-3 py-2 bg-[#f8f9fa] focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
            
            <div>
                <div class="flex justify-between mb-1">
                    <label class="block text-sm font-medium">Contraseña</label>
                    <a href="#" class="text-sm text-blue-600 hover:underline">¿Olvidaste tu contraseña?</a>
                </div>
                <input type="password" name="password" placeholder="••••••••••••" required 
                       class="w-full border border-gray-300 rounded px-3 py-2 bg-[#f8f9fa] focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full bg-[#1a56db] hover:bg-blue-700 text-white font-medium py-2.5 rounded transition duration-200">
                Iniciar Sesión
            </button>
        </form>

        <div class="mt-16 text-center text-sm text-gray-500 font-medium">
            <p>Oficina de Tecnologías de la Información</p>
            <p>UNHEVAL</p>
        </div>

    </div>
</body>
</html>