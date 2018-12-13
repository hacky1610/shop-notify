

function Convert {
    param(
        [Parameter(Mandatory)][string]$source,
         [Parameter(Mandatory)][string]$destination
    )

    [xml]$phpUnitTestResults = Get-Content $source
    $newXml = '<?xml version="1.0" encoding="UTF-8"?><testsuites>'
    $newXml += $phpUnitTestResults.testsuites.testsuite.InnerXml
    $newXml += '</testsuites>'
    $newXml | Out-File -filepath $destination


}