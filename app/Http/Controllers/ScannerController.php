<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;

class ScannerController extends Controller
{
    public function index()
    {
        return view('scanner.index'); // You'll create this view later
    }

    public function analyze(Request $request)
    {
        $request->validate([
            'resume' => 'required|mimes:pdf|max:2048',
            'job_description' => 'required|string',
        ]);

        // 1. Extract Text from PDF
        $parser = new Parser();
        $pdf = $parser->parseFile($request->file('resume')->path());
        $resumeText = strtolower($pdf->getText());
        
        // 2. Process Job Description
        $jobText = strtolower($request->job_description);
        
        // 3. Define "Keywords" (Simple method: Split by space, remove common words)
        // In a real app, you'd use a predefined list of Tech Skills (PHP, SQL, Laravel, etc.)
        $commonWords = ['the', 'and', 'is', 'in', 'to', 'for', 'with', 'a', 'an', 'of'];
        $words = explode(' ', $jobText);
        $keywords = array_filter($words, function($word) use ($commonWords) {
            $cleanWord = preg_replace('/[^a-z0-9]/', '', $word); // Remove punctuation
            return strlen($cleanWord) > 2 && !in_array($cleanWord, $commonWords);
        });
        
        $keywords = array_unique($keywords); // Remove duplicates
        $totalKeywords = count($keywords);
        
        // 4. Check for matches
        $found = [];
        $missing = [];
        
        foreach ($keywords as $word) {
            $cleanWord = preg_replace('/[^a-z0-9]/', '', $word);
            if (str_contains($resumeText, $cleanWord)) {
                $found[] = $cleanWord;
            } else {
                $missing[] = $cleanWord;
            }
        }
        
        // 5. Calculate Score
        $score = $totalKeywords > 0 ? round((count($found) / $totalKeywords) * 100) : 0;

        return back()->with([
            'score' => $score,
            'missing' => array_slice($missing, 0, 10), // Show top 10 missing
            'found_count' => count($found)
        ]);
    }
}