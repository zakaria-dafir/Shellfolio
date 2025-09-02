cat << 'EOF' > README.md
# Shellfolio – Zakaria Dafir

Shellfolio est un portfolio interactif en mode terminal, développé avec **Laravel**, permettant de naviguer dans le profil d’un développeur Full Stack via des commandes personnalisées. Il est inspiré des "terminal portfolios" pour présenter les compétences, projets et contacts de manière originale.

---

## Fonctionnalités

- Terminal interactif pour consulter le CV et les projets.
- Commandes disponibles :
  - `help` : Affiche la liste des commandes disponibles.
  - `about` : Présente l’auteur et son parcours.
  - `skills` : Affiche les compétences techniques.
  - `projects` : Liste tous les projets.
  - `project <id>` : Affiche un projet spécifique.
  - `contact` : Affiche email et réseaux sociaux.
  - `github` : Lien vers GitHub.
  - `linkedin` : Lien vers LinkedIn.
  - `cv` : Lien pour télécharger le CV.
  - `clear` : Nettoie l’écran.

- JSON responses pour intégration front-end si nécessaire.

---

## Installation

1. Clonez le projet :

\`\`\`bash
git clone https://github.com/zakaria-dafir/shellfolio.git
cd shellfolio
\`\`\`

2. Installez les dépendances Laravel :

\`\`\`bash
composer install
\`\`\`

3. Configurez votre `.env` (connexion à la base de données si nécessaire) :

\`\`\`env
APP_NAME=Shellfolio
APP_URL=http://localhost
\`\`\`

4. Lancez le serveur Laravel :

\`\`\`bash
php artisan serve
\`\`\`

---

## Structure

- `app/Http/Controllers/TerminalController.php` : Controller principal contenant les données du CV et les méthodes de commande.
- `routes/web.php` : Route pour interagir avec le terminal (`/terminal` par exemple).
- `public/` : Fichiers statiques si besoin (CSS, JS pour terminal front-end).
- `zackscv.pdf` : CV téléchargeable.

---

## Commandes principales

### Aide

\`\`\`bash
help
\`\`\`
Affiche toutes les commandes disponibles.

### Profil

\`\`\`bash
about
\`\`\`
Affiche les informations personnelles et le résumé du développeur.

### Compétences

\`\`\`bash
skills
\`\`\`
Liste toutes les compétences techniques.

### Projets

\`\`\`bash
projects
\`\`\`
Liste tous les projets avec ID, nom et description.

\`\`\`bash
project 1
\`\`\`
Affiche un projet spécifique par ID.

### Contact

\`\`\`bash
contact
\`\`\`
Affiche email et réseaux sociaux.

### Liens rapides

\`\`\`bash
github
linkedin
cv
\`\`\`

---

## Contribuer

Les contributions sont les bienvenues ! Vous pouvez :

- Ajouter de nouvelles commandes.
- Ajouter des projets ou compétences.
- Améliorer le front-end si vous avez une interface web pour le terminal.

---

## Licence

Ce projet est sous licence MIT.

---

## Auteur

**Zakaria Dafir** – [GitHub](https://github.com/zakaria-dafir) – [LinkedIn](https://www.linkedin.com/in/zakaria-dafir-9b5207275/)
EOF
