<h1 align="center">🚀 Audit Biometrie – Laravel Application</h1>
<p align="center"><strong>Gestion des opérations d'audit et de mise à jour des empreintes digitales (Biométrie)</strong></p>

---

## ✨ Sommaire
1. Vue d'ensemble
2. Fonctionnalités principales
3. Architecture & Modèles
4. Prérequis
5. Installation & Démarrage (Windows & WSL)
6. Commandes utiles
7. Flux de travail : Création & Édition d'un audit
8. Sélecteur multi-employés (Alpine.js)
9. Export / Filtrage des audits
10. Structure des tables (résumé)
11. Dépannage (Troubleshooting)
12. Roadmap / Améliorations futures

---

## 1. 🧭 Vue d'ensemble
Application Laravel permettant :
- La création et le suivi d'audits de mise à jour biométrique.
- L'association de plusieurs fonctionnaires (agents) à chaque audit.
- Le filtrage et l'export (Excel) des audits.

## 2. 🔑 Fonctionnalités principales
- Authentification Laravel standard.
- Gestion des établissements (sélection dans le formulaire d'audit).
- Relations Eloquent : `Audit` ↔ `Fonctionnaire` (Many-to-Many).
- Sélecteur dynamique multi-fonctionnaires (Alpine.js) avec pré-remplissage en mode édition.
- Validation côté serveur (Requests) + messages d'erreurs.
- Export des audits filtrés (période / établissement).

## 3. 🧱 Architecture & Modèles
Modèles principaux :
- `User` : utilisateur authentifié (créateur d'audits).
- `Audit` : représente une opération (champs : etab_id, date_audit, compteurs numériques...).
- `Fonctionnaire` : agent (full_name, phone, matricule, is_deleted).
- Pivot : `audit_fonctionnaire` (audit_id, fonctionnaire_id).

Relations :
```php
Audit::belongsTo(Etablissements::class, 'etab_id');
Audit::belongsToMany(Fonctionnaire::class, 'audit_fonctionnaire');
Fonctionnaire::belongsToMany(Audit::class, 'audit_fonctionnaire');
```

## 4. ✅ Prérequis
- PHP 8.2+ (project runs on 8.4 currently).
- Composer
- Node.js 18+ (pour Vite / build assets)
- SQLite (fichier `database/database.sqlite`) ou MySQL (adapter `.env`).
- Extension PHP : pdo, mbstring, openssl, tokenizer, ctype, json, xml.

## 5. ⚙️ Installation & Démarrage
### Cloner & installer
```bash
git clone <repo-url>
cd audit-biometrie
composer install
npm install
```

### Fichier d'environnement
```bash
cp .env.example .env  # sous Windows copier manuellement si nécessaire
php artisan key:generate
```

Configurer la base (ex. SQLite) :
```bash
touch database/database.sqlite
php artisan migrate --seed
```

### Lancer les serveurs
```bash
php artisan serve
npm run dev   # (ou: npm run build en production)
```

Accéder à l'application : http://localhost:8000

## 6. 🧰 Commandes utiles
```bash
php artisan migrate:fresh --seed
php artisan tinker
php artisan route:list
php artisan cache:clear
php artisan config:clear
npm run build
```

## 7. 📝 Flux de travail : Création & Édition
1. Aller sur /audits/create.
2. Sélectionner un établissement + date.
3. Ajouter un ou plusieurs fonctionnaires via la liste déroulante.
4. Saisir les compteurs (nb_detenus, nb_edited_fingerprints, etc.).
5. Sauvegarder.
6. En édition (/audits/{id}/edit), les fonctionnaires déjà liés sont préchargés.

## 8. 👥 Sélecteur multi-fonctionnaires (Alpine.js)
Le composant vit dans `resources/views/user/audits/form.blade.php`.

Principe :
- Les fonctionnaires sélectionnés sont conservés dans `fonctionnaires` (tableau d'objets `{id, name}`).
- À chaque ajout, un `<input type="hidden" name="fonctionnaires[]" value="ID">` est injecté pour l'envoi au serveur.
- En mode édition, `$selectedFonctionnaires` est injecté en JSON dans `x-data` puis normalisé dans `init()`. 

Extrait clé :
```html
<div x-data='fonctionnairesSelector(@json($selectedFonctionnaires ?? []))' x-init="init()">
	<!-- select + liste -->
</div>
```

Si les noms ne s'affichent pas en édition :
1. Vérifier la console pour `Existing fonctionnaires raw:`.
2. Contrôler le JSON rendu dans le HTML (View Source).
3. Confirmer que la relation pivot contient bien les liens.

## 9. 📤 Export & Filtrage
Vue : `user/audits/export-form.blade.php`.
Filtres disponibles : établissement + intervalle de dates.
Export Excel via `AuditExport` (Maatwebsite\Excel).

## 10. 🗄️ Structure des tables (résumé)
```
audits: id, user_id, etab_id, date_audit, nb_detenus, nb_edited_fingerprints, nb_verified_fingerprints, nb_without_fingerprints, timestamps
fonctionnaires: id, full_name, phone, matricule, is_deleted, timestamps
audit_fonctionnaire: audit_id, fonctionnaire_id
users: id, name, email, password, role, timestamps
```

## 11. 🐞 Dépannage
| Problème | Cause probable | Solution |
|----------|----------------|----------|
| Les fonctionnaires ne se chargent pas en édition | JSON mal sérialisé ou vide | Vérifier `$selectedFonctionnaires` dans le contrôleur (`edit()`). |
| Aucune sélection n'est ajoutée | `addFonctionnaire()` non déclenché | Vérifier `@change="addFonctionnaire()"` et absence d'erreur JS. |
| Erreur 419 CSRF | Session expirée | Recharger la page / vérifier balise `@csrf`. |
| Assets manquants (vite manifest) | Build non exécuté | Lancer `npm run dev` ou `npm run build`. |
| Données perdues après erreur | Validation redirige | Vérifier `old()` dans les champs, messages d'erreur. |

## 12. 🚧 Roadmap / Améliorations futures
- Validation front (limiter duplications visuelle immédiate).
- Pagination / recherche des audits.
- Soft-delete / restauration des fonctionnaires.
- Tests (Pest) pour : création audit, édition avec mise à jour pivot, export filtré.
- Optimisation performance (eager loading ciblé, index DB sur foreign keys).
- Refactor composant Alpine vers un composant Blade + module JS dédié.

---
### Licence
Projet interne basé sur Laravel (MIT). Adapter selon politique interne si nécessaire.

### Contact
Ajouter les points de contact internes ici (email / Slack / etc.).

---
> Ce fichier README remplace la documentation Laravel générique afin de refléter l'état réel du projet et ses besoins spécifiques.
