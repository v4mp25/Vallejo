<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Importar Profesores</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Cargar Docentes</h2>

        <form action="/importar" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <div>
                <label class="block mb-2 font-medium text-gray-700">Selecciona tu Excel (.xlsx):</label>
                <input type="file" name="documento" accept=".xlsx, .csv" required 
                       class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:border-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold p-2 rounded hover:bg-blue-700 transition">
                Importar Datos
            </button>
        </form>
    </div>
</body>
</html>