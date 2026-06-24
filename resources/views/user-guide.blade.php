<x-layouts::app :title="__('User Guide')">
    <div class="mx-auto max-w-4xl space-y-8 p-6">
        <div>
            <h1 class="text-3xl font-bold">{{ __('User Guide') }}</h1>
            <p class="mt-2 text-zinc-600 dark:text-zinc-400">Learn how to use the TELC Exam Platform effectively.</p>
        </div>

        <section class="space-y-4 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
            <h2 class="text-xl font-semibold">Getting Started</h2>
            <p>Welcome to the TELC Exam Platform. This guide will help you understand how to navigate the platform, take exams, and interpret your results.</p>
            <ul class="list-disc space-y-1 pl-6">
                <li><strong>Dashboard</strong> &mdash; Your main hub. Students see available exams; admins see the exam builder and student performance.</li>
                <li><strong>Voice Practice</strong> &mdash; Practice speaking with AI-powered feedback using speech recognition.</li>
                <li><strong>Free Exam</strong> &mdash; Try any published exam for free (limited attempts per session).</li>
                <li><strong>Pricing</strong> &mdash; View available subscription plans and choose one that fits your needs.</li>
                <li><strong>Billing</strong> &mdash; Manage your subscription and view payment history.</li>
            </ul>
        </section>

        <section class="space-y-4 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
            <h2 class="text-xl font-semibold">Taking an Exam</h2>
            <ol class="list-decimal space-y-2 pl-6">
                <li>Navigate to the <strong>Dashboard</strong> and select an exam from the catalog.</li>
                <li>Click <strong>Buy Exam / Enroll</strong> or <strong>Start Free Exam</strong> to begin.</li>
                <li>Work through each section within the allotted time. The timer counts down automatically.</li>
                <li>For multiple-choice questions, select your answer from the available options.</li>
                <li>For fill-in-the-blank tasks, type your answer in the text field.</li>
                <li>For inline gap exercises, click on a gap to see the available options and select one.</li>
                <li>When finished, click <strong>Submit & Mark</strong> to see your score.</li>
            </ol>
        </section>

        <section class="space-y-4 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
            <h2 class="text-xl font-semibold">Voice Practice</h2>
            <p>The Voice Practice feature uses speech recognition and AI to help you improve your spoken German.</p>
            <ol class="list-decimal space-y-2 pl-6">
                <li>Go to <strong>Voice Practice</strong> from the sidebar.</li>
                <li>Read the question displayed on screen.</li>
                <li>Click <strong>Start Recording</strong> and speak your answer.</li>
                <li>Click <strong>Stop Recording</strong> when you are done.</li>
                <li>Review the transcribed text and edit if needed.</li>
                <li>Click <strong>Analyze with AI</strong> to receive feedback on your response.</li>
            </ol>
        </section>

        <section class="space-y-4 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
            <h2 class="text-xl font-semibold">Understanding Results</h2>
            <p>After submitting an exam, you will see:</p>
            <ul class="list-disc space-y-1 pl-6">
                <li><strong>Total Score</strong> &mdash; Your earned points out of the total possible.</li>
                <li><strong>Percentage</strong> &mdash; Your overall score as a percentage.</li>
                <li><strong>Per-Question Results</strong> &mdash; Correct answers are marked in green; incorrect ones show the expected answer.</li>
                <li><strong>Explanations</strong> &mdash; For incorrect answers, an AI-generated explanation helps you understand the correct response.</li>
            </ul>
        </section>

        <section class="space-y-4 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
            <h2 class="text-xl font-semibold">Subscription & Billing</h2>
            <p>The platform offers subscription plans for unlimited access:</p>
            <ul class="list-disc space-y-1 pl-6">
                <li>Free users get a limited number of exam attempts per session.</li>
                <li>Subscribers enjoy unlimited exam attempts and all features.</li>
                <li>Visit the <strong>Pricing</strong> page to view plans and subscribe.</li>
                <li>Manage your subscription in the <strong>Billing</strong> section.</li>
            </ul>
        </section>

        <section class="space-y-4 rounded-lg border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-800">
            <h2 class="text-xl font-semibold">Need Help?</h2>
            <p>If you encounter any issues or have questions, please contact your platform administrator.</p>
        </section>
    </div>
</x-layouts::app>
