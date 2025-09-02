<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TerminalController extends Controller
{
    private string $name = 'Zakaria Dafir';
    private string $role = 'Full Stack Developer';
    private string $location = 'Agadir, Morocco';
    private string $email = 'zakariadafir209@gmail.com';
    private string $github = 'https://github.com/zakaria-dafir';
    private string $linkedin = 'https://www.linkedin.com/in/zakaria-dafir-9b5207275/';
    private string $cvUrl = 'zackscv.pdf';

    private string $about = "Junior Laravel dev. Love building clean APIs and small tools.\n1337 pool gave me solid C + shell basics. Hungry to learn and ship.";

    private array $skills = [
        'Langages :',
        'HTML · CSS · JavaScript · PHP · SQL · Python · C',

        'Frameworks et Technologies :',
        'Laravel · React · Bootstrap · Node.js · Git · MySQL · Redis · REST APIs · Queues',

        'Outils et Environnements :',
        'VS Code · GitHub · Linux · Docker (bases) · Figma · Bash/Shell',
    ];

    private array $projects = [
    [
        'id' => 1,
        'name' => 'Application E-commerce',
        'desc' => 'Développement d’un site e-commerce avec gestion des produits, panier et système de paiement simulé. Interface moderne et responsive (HTML/CSS/JS) et API simple pour gérer les produits et utilisateurs.',
        'tech' => 'JavaScript (Full Stack)',
        'url' => '' // tu peux ajouter un lien si disponible
    ],
    [
        'id' => 2,
        'name' => 'Plateforme de Location de Voitures',
        'desc' => 'Conception d’une plateforme de réservation de voitures avec tableau de bord administrateur. Back-end Laravel (gestion utilisateurs, réservations, facturation) et Front-end React (interface fluide et réactive).',
        'tech' => 'Laravel & React',
        'url' => '' // tu peux ajouter un lien si disponible
    ],
    [
        'id' => 3,
        'name' => 'Système de Gestion d’Hôtel',
        'desc' => 'Développement d’un logiciel pour gérer les réservations, chambres et clients. Base de données SQLite et interface simple avec Tkinter.',
        'tech' => 'Python',
        'url' => '' // tu peux ajouter un lien si disponible
    ],
];

    public function handle(Request $request)
    {
        $input = trim((string) $request->input('cmd', ''));
        if ($input === '') {
            return response()->json(['output' => '']);
        }

        $tokens = preg_split('/\s+/', $input);
        $cmd = strtolower(array_shift($tokens));
        $args = $tokens;

        switch ($cmd) {
            case 'help':
                $out = $this->help();
                break;
            case 'about':
                $out = $this->about();
                break;
            case 'skills':
                $out = $this->skills();
                break;
            case 'projects':
                $out = $this->projectsList();
                break;
            case 'project':
                $out = $this->projectShow($args);
                break;
            case 'contact':
                $out = $this->contact();
                break;
            case 'github':
                $out = $this->link($this->github, 'GitHub');
                break;
            case 'linkedin':
                $out = $this->link($this->linkedin, 'LinkedIn');
                break;
            case 'cv':
                $out = $this->link($this->cvUrl, 'Download CV');
                break;
            case 'clear':
                return response()->json(['clear' => true]);
            default:
                $out = "Command not found: {$cmd}\nType: help";
        }

        return response()->json(['output' => $out]);
    }

    private function help(): string
    {
        return implode("\n", [
            "Available commands:",
            "  help          Show this help",
            "  about         Who I am",
            "  skills        Tech I use",
            "  projects      List projects",
            "  project <id>  Show one project",
            "  contact       Email + socials",
            "  github        Open GitHub",
            "  linkedin      Open LinkedIn",
            "  cv            CV link",
            "  clear         Clear the screen",
        ]);
    }

    private function about(): string
    {
        return "{$this->name} — {$this->role}\n{$this->location}\n\n{$this->about}";
    }

    private function skills(): string
    {
        return "Skills:\n- " . implode("\n- ", $this->skills);
    }

    private function projectsList(): string
{
    $lines = ["Projects:"];
    foreach ($this->projects as $p) {
        $lines[] = "[{$p['id']}] {$p['name']} ({$p['tech']})";
        $lines[] = "    {$p['desc']}";
        if (!empty($p['url'])) {
            $lines[] = "    {$p['url']}";
        }
    }
    return implode("\n", $lines);
}


private function projectShow(array $args): string
{
    $id = (int)($args[0] ?? 0);
    if (!$id) {
        return "Usage: project <id>\nExample: project 1";
    }
    foreach ($this->projects as $p) {
        if ($p['id'] === $id) {
            $output  = "[{$p['id']}] {$p['name']} ({$p['tech']})\n";
            $output .= "{$p['desc']}\n";
            if (!empty($p['url'])) {
                $output .= "{$p['url']}\n";
            }
            return $output;
        }
    }
    return "Project not found: {$id}\nTry: projects";
}



    private function contact(): string
    {
        return "Email: {$this->email}\nGitHub: " . $this->link($this->github, $this->github) .
               "\nLinkedIn: " . $this->link($this->linkedin, $this->linkedin);
    }

    private function link(string $url, string $text): string
    {
        $u = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        $t = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        return "<a href=\"{$u}\" target=\"_blank\" rel=\"noopener noreferrer\">{$t}</a>";
    }

}