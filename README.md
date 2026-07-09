# ErFolg — German Language Learning and Exams System

ErFolg is a comprehensive, production-ready German language learning and mock exam preparation platform. It is engineered specifically to help students pass official **TELC** examinations across multiple language proficiency levels (**B1, B2, C1, C2**).

This software is proprietary and belongs to **HASENGINEZA Theodore**.

---

## Key Features

1. **AI-Powered Oral Speaking Exams**
   * Multi-stage, interactive oral exams powered by the **Google Gemini 2.5 Flash** model.
   * Traverses a complete 3-step speaking assessment:
     * **Step 1a: Presentation** (The student holds a presentation on a chosen topic).
     * **Step 1b: Reaction** (The student responds to a generated presentation on the alternative topic).
     * **Step 2: Discussion** (The student and AI-Prüfer engage in a multi-turn dialogue on a conversational topic).
   * Generates task-specific rubrics (Präsentation, Reaktion, Diskussion) and a holistic global score based on the entire transcript.

2. **Secure Study Materials Delivery**
   * Secure, symlink-free serving endpoints (`/books-pdf/{book}` and `/books-cover/{book}`) that directly read and stream books from storage.

3. **Database Driver Compatibility**
   * Dynamic DB driver detection that supports **SQLite** (local development/testing) and **MySQL / MariaDB** (production servers) without SQL syntax compatibility exceptions.

4. **Multi-Role User Manuals & Diagnostic Tools**
   * Role-based filtered manuals for **Students**, **Administrators**, and **Owners** with optimized print styling.
   * Built-in diagnostic panels that hide internal system path mappings from students while providing full diagnostics to system admins.

5. **Audio Practice Limits & Security**
   * Restricts free tier/guest access on speaking mock tests based on custom administrative settings (`free_section_limit`).
   * Raised validation limits to support uploading up to **100MB** audio listening practice files.

---

## Setup and Installation

### Prerequisites
* **PHP**: 8.2 or higher (supports PHP 8.5)
* **Composer**
* **Node.js & npm**
* **SQLite / MySQL / MariaDB**

### Installation Steps

1. **Clone the Repository** and navigate to the project directory:
   ```bash
   cd Telc
   ```

2. **Configure Environment Variables**:
   Copy `.env.example` to `.env` and fill in your database credentials and API keys:
   ```bash
   cp .env.example .env
   ```
   Ensure you set the following crucial variables:
   * `GEMINI_API_KEY`: Google AI Studio developer API key.
   * `DB_CONNECTION`: `sqlite` (default) or `mysql`.
   * PayPal, Stripe, and Revolut credentials.

3. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

4. **Generate App Key**:
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations and Seeders**:
   This sets up the tables and populates the database with exam templates, study modules, settings, and the default administrator user:
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Build Frontend Assets**:
   ```bash
   npm run build
   ```

7. **Start the Application**:
   Start the Laravel development server:
   ```bash
   php artisan serve
   ```
   Or run the concurrent developer setup:
   ```bash
   composer dev
   ```

---

## Code Quality & Verification

To run styling checks (Laravel Pint), static type analysis (PHPStan), and automated feature tests (Pest), execute:
```bash
composer test
```
* All configuration cache is cleared automatically before testing.
* Linting and type-safety validations run at strict production standards with 0 errors.

---

## License

This software is licensed under the MIT License. See the [LICENSE](file:///C:/Users/USER%201/Desktop/Proposals/Telc/Telc/LICENSE) file for the full license text.
