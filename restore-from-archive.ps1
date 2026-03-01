param(
    [Parameter(Position = 0)]
    [string[]]$Paths,

    [switch]$All,
    [switch]$DryRun
)

$ErrorActionPreference = 'Stop'
$repoRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$archiveRoot = Join-Path $repoRoot 'archive'

function Show-Usage {
    Write-Host 'Usage:'
    Write-Host '  .\\restore-from-archive.ps1 -All [-DryRun]'
    Write-Host '  .\\restore-from-archive.ps1 <relative-path> [<relative-path> ...] [-DryRun]'
    Write-Host ''
    Write-Host 'Examples:'
    Write-Host '  .\\restore-from-archive.ps1 style1.css images\\backg.jfif -DryRun'
    Write-Host '  .\\restore-from-archive.ps1 -All'
}

function Normalize-RelPath([string]$path) {
    $p = $path.Trim()
    $p = $p -replace '/', '\\'
    $p = $p.TrimStart('\\')
    if ($p -like 'archive\\*') {
        $p = $p.Substring(8)
    }
    return $p
}

if (-not (Test-Path -LiteralPath $archiveRoot)) {
    throw "archive folder not found: $archiveRoot"
}

if ($All -and $Paths) {
    throw 'Use either -All or explicit paths, not both.'
}

if (-not $All -and (-not $Paths -or $Paths.Count -eq 0)) {
    Show-Usage
    exit 1
}

$targets = @()
if ($All) {
    $targets = Get-ChildItem -LiteralPath $archiveRoot -Recurse -File
} else {
    foreach ($raw in $Paths) {
        $rel = Normalize-RelPath $raw
        if ([string]::IsNullOrWhiteSpace($rel)) {
            continue
        }

        $src = Join-Path $archiveRoot $rel
        if (Test-Path -LiteralPath $src -PathType Leaf) {
            $targets += Get-Item -LiteralPath $src
        } else {
            Write-Warning "Not found in archive: $rel"
        }
    }
}

if (-not $targets -or $targets.Count -eq 0) {
    Write-Host 'No files selected for restore.'
    exit 0
}

$restored = 0
$skipped = 0

foreach ($item in $targets) {
    $rel = $item.FullName.Substring($archiveRoot.Length).TrimStart('\\')
    $dest = Join-Path $repoRoot $rel
    $destDir = Split-Path -Parent $dest

    if (Test-Path -LiteralPath $dest) {
        Write-Warning "Skipping existing file: $rel"
        $skipped++
        continue
    }

    if ($DryRun) {
        Write-Host "[DryRun] Restore: $rel"
        continue
    }

    if (-not (Test-Path -LiteralPath $destDir)) {
        New-Item -ItemType Directory -Path $destDir -Force | Out-Null
    }

    Move-Item -LiteralPath $item.FullName -Destination $dest
    Write-Host "Restored: $rel"
    $restored++
}

if ($DryRun) {
    Write-Host "Dry run complete. Candidate files: $($targets.Count), skipped-existing: $skipped"
} else {
    Write-Host "Restore complete. Restored: $restored, skipped-existing: $skipped"
}
