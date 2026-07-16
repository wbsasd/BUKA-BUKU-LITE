param(
  [string]$Root = 'c:/xampp/htdocs/BUKA-BUKU-LITE',
  [string]$OutFile = (Join-Path 'c:/xampp/htdocs/BUKA-BUKU-LITE' 'audit_global_role_membership.txt')
)

$ErrorActionPreference = 'SilentlyContinue'

$paths = @('app','bootstrap','config','database','routes','resources')

# Multi-pattern scan (case-insensitive, SimpleMatch)
$patterns = @(
  'premium',
  'pengguna',
  'role',
  'membership_status',
  'active',
  'pending',
  'User::create',
  'User::update',
  'forceFill',
  'fill',
  'save(',
  'update(',
  'observer',
  'listener',
  'event',
  'mutator',
  'creating',
  'created',
  'registered',
  'authenticated',
  'login',
  'register'
)

'' | Out-File -Encoding UTF8 $OutFile
('SCAN START ' + (Get-Date)) | Out-File -Encoding UTF8 $OutFile

foreach($rel in $paths){
  $p = Join-Path $Root $rel
  if(!(Test-Path $p)){
    continue
  }

  $files = Get-ChildItem -Path $p -Recurse -File -ErrorAction SilentlyContinue
  foreach($f in $files){
    $file = $f.FullName

    try{
      $matches = Select-String -Path $file -Pattern $patterns -SimpleMatch -CaseSensitive:$false -List -ErrorAction SilentlyContinue
      if($null -ne $matches){
        ('--- ' + $file) | Out-File -Encoding UTF8 $OutFile -Append
        foreach($m in $matches){
          # only line text (keeps output compact)
          ($m.Line) | Out-File -Encoding UTF8 $OutFile -Append
        }
      }
    } catch {
      # ignore individual-file failures
    }
  }
}

('SCAN END ' + (Get-Date)) | Out-File -Encoding UTF8 $OutFile -Append
Write-Output ('Wrote: ' + $OutFile)

