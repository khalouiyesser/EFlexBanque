<?php

namespace App\Service;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class AnalyseCv
{
    public function analyseCV(string $cv, array $motsCles): int
    {
        $pythonScriptPath = 'C:\Users\Yesser\PI\InnovatixYesser\API\Analyseur.py';
        $process = new Process(['python', $pythonScriptPath, $cv, implode(',', $motsCles)]);
        $process->run();
        
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        
        return (int) $process->getOutput();
    }
}
