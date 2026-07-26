# ============================================================================
# build-theme-zip.ps1
# Empaqueta el tema "casadepiedra-luxury-theme" para WordPress produccion.
#
# SOLUCION: WordPress requiere barras diagonales "/" dentro del ZIP.
# Las herramientas nativas de Windows (Compress-Archive, Explorer) usan "\"
# lo que provoca errores fatales al intentar instalar el tema en WP.
#
# Este script usa System.IO.Compression con rutas corregidas a "/".
# ============================================================================

param(
    [string]$ThemeDir = "casadepiedra-luxury-theme",
    [string]$OutputZip = "casadepiedra-luxury-theme-production.zip"
)

$ErrorActionPreference = "Stop"
Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

$repoRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$themePath = Join-Path $repoRoot $ThemeDir
$outputPath = Join-Path $repoRoot $OutputZip

if (-not (Test-Path $themePath)) {
    Write-Error "No se encontro el directorio del tema: $themePath"
    exit 1
}

# Eliminar ZIP anterior si existe
if (Test-Path $outputPath) {
    Remove-Item $outputPath -Force
    Write-Host "[OK] ZIP anterior eliminado." -ForegroundColor Yellow
}

# Patrones a excluir del empaquetado (archivos innecesarios en produccion)
$excludePatterns = @(
    '\.git',
    '\.DS_Store',
    'Thumbs\.db',
    'node_modules',
    '\.log$',
    'debug\.log',
    '\.map$'
)

function ShouldExclude($relativePath) {
    foreach ($pattern in $excludePatterns) {
        if ($relativePath -match $pattern) {
            return $true
        }
    }
    return $false
}

Write-Host ""
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host " Casa de Piedra - Theme Packager (WP Ready)" -ForegroundColor Cyan
Write-Host "=============================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Origen : $themePath" -ForegroundColor Gray
Write-Host "Destino: $outputPath" -ForegroundColor Gray
Write-Host ""

# Crear el ZIP con rutas usando "/" (compatible con WordPress)
$zipStream = [System.IO.File]::Create($outputPath)
$archive = New-Object System.IO.Compression.ZipArchive($zipStream, [System.IO.Compression.ZipArchiveMode]::Create)

$fileCount = 0
$totalBytes = 0

# Obtener todos los archivos del tema
$allFiles = Get-ChildItem -Path $themePath -Recurse -File

foreach ($file in $allFiles) {
    # Calcular ruta relativa desde el directorio padre del tema
    $relativePath = $file.FullName.Substring($repoRoot.Length + 1)

    # Verificar exclusiones
    if (ShouldExclude $relativePath) {
        continue
    }

    # CRITICO: Convertir barras invertidas "\" a barras diagonales "/"
    $zipEntryName = $relativePath -replace '\\', '/'

    # Crear entrada en el ZIP
    $entry = $archive.CreateEntry($zipEntryName, [System.IO.Compression.CompressionLevel]::Optimal)

    # Copiar contenido del archivo a la entrada
    $entryStream = $entry.Open()
    $fileStream = [System.IO.File]::OpenRead($file.FullName)
    $fileStream.CopyTo($entryStream)
    $fileStream.Close()
    $entryStream.Close()

    $fileCount++
    $totalBytes += $file.Length
}

$archive.Dispose()
$zipStream.Close()

$zipSize = (Get-Item $outputPath).Length
$zipSizeMB = [math]::Round($zipSize / 1MB, 2)

Write-Host ""
Write-Host "=============================================" -ForegroundColor Green
Write-Host " EMPAQUETADO EXITOSO" -ForegroundColor Green
Write-Host "=============================================" -ForegroundColor Green
Write-Host ""
Write-Host "  Archivos incluidos : $fileCount" -ForegroundColor White
Write-Host "  Tamano total ZIP   : $zipSizeMB MB" -ForegroundColor White
Write-Host "  Archivo generado   : $OutputZip" -ForegroundColor White
Write-Host ""

# Verificar que las rutas dentro del ZIP usan "/"
Write-Host "--- Verificacion de rutas internas (primeras 15) ---" -ForegroundColor Cyan
$verifyStream = [System.IO.File]::OpenRead($outputPath)
$verifyArchive = New-Object System.IO.Compression.ZipArchive($verifyStream, [System.IO.Compression.ZipArchiveMode]::Read)
$verifyCount = 0
foreach ($entry in $verifyArchive.Entries) {
    if ($verifyCount -ge 15) { break }
    if ($entry.FullName -match '\\') {
        Write-Host "  [ERROR: backslash] $($entry.FullName)" -ForegroundColor Red
    } else {
        Write-Host "  [OK] $($entry.FullName)" -ForegroundColor Green
    }
    $verifyCount++
}
$totalEntries = $verifyArchive.Entries.Count

# Verificar que NO haya backslashes en NINGUNA entrada
$backslashEntries = $verifyArchive.Entries | Where-Object { $_.FullName -match '\\' }
$backslashCount = 0
if ($backslashEntries) { $backslashCount = @($backslashEntries).Count }
$verifyArchive.Dispose()
$verifyStream.Close()

Write-Host ""
if ($backslashCount -eq 0) {
    Write-Host "  VERIFICACION COMPLETA: 0 rutas con barras invertidas." -ForegroundColor Green
    Write-Host "  Total de entradas en el ZIP: $totalEntries" -ForegroundColor White
    Write-Host ""
    Write-Host "  El archivo esta listo para subir a WordPress." -ForegroundColor Green
    Write-Host "  Ruta: Apariencia - Temas - Anadir nuevo - Subir tema." -ForegroundColor Green
} else {
    Write-Host "  ADVERTENCIA: Se encontraron $backslashCount rutas con barras invertidas." -ForegroundColor Red
}

Write-Host ""
