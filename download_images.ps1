$json = Get-Content "C:\Users\johan\Local Sites\casa-de-piedra\app\public\casa_export.json" -Raw | ConvertFrom-Json;
$mirror_dir = "C:\Users\johan\OneDrive\Desktop\repos git\CASA\casadepiedra-luxury-theme\assets\images\mirror";
if (-not (Test-Path $mirror_dir)) { New-Item -ItemType Directory -Path $mirror_dir }

$urls = @();
foreach ($prop in $json.options.psobject.properties) {
    $val = $prop.value
    if ($val -match "^http.*(jpg|jpeg|png|webp|gif|svg)$") {
        $urls += $val
    }
}
foreach ($e in $json.espacios) { if ($e.thumb) { $urls += $e.thumb } }
foreach ($r in $json.restaurantes) { if ($r.thumb) { $urls += $r.thumb } }
foreach ($g in $json.galeria) { $urls += $g }

$urls = $urls | Select-Object -Unique

foreach ($url in $urls) {
    $filename = Split-Path -Leaf $url
    $dest = Join-Path $mirror_dir $filename
    if (-not (Test-Path $dest)) {
        Write-Host "Downloading $url"
        try {
            Invoke-WebRequest -Uri $url -OutFile $dest -UseBasicParsing -ErrorAction Stop
        } catch {
            Write-Host "Failed to download $url"
        }
    }
}
Write-Host "Images downloaded."
