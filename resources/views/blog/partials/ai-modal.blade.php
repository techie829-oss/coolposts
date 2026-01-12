<!-- AI Content Modal -->
<div id="aiContentModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-md transition-opacity" onclick="closeAIContentModal()">
    </div>

    <div
        class="relative bg-white/90 backdrop-blur-2xl border border-white/40 rounded-[2.5rem] max-w-4xl w-full max-h-[90vh] flex flex-col shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-8 border-b border-gray-100/50">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-2xl bg-brand-gradient flex items-center justify-center text-white shadow-lg shadow-purple-200">
                    <i class="fas fa-magic text-xl"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-extrabold text-gray-900 tracking-tight">AI Generated Content</h3>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1">Review and refine
                        your masterpiece</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button onclick="copyAIContent()"
                    class="px-5 py-2.5 bg-gray-50 text-gray-600 rounded-2xl font-bold hover:bg-gray-100 transition-all flex items-center gap-2 border border-gray-100">
                    <i class="fas fa-copy"></i>
                    <span class="hidden sm:inline">Copy</span>
                </button>
                <button onclick="closeAIContentModal()"
                    class="w-12 h-12 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center hover:bg-red-50 hover:text-red-500 transition-all">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-8 overflow-y-auto flex-1 bg-gray-50/30">
            <div id="aiGeneratedContent"
                class="prose prose-purple max-w-none text-gray-700 font-medium leading-relaxed whitespace-pre-wrap selection:bg-purple-100">
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-8 border-t border-gray-100/50 flex items-center justify-between bg-white/50">
            <button onclick="regenerateAIContent()"
                class="px-6 py-3 bg-white text-purple-600 border border-purple-100 rounded-2xl font-bold hover:bg-purple-50 transition-all flex items-center gap-2 shadow-sm font-semibold">
                <i class="fas fa-redo"></i>
                Try Again
            </button>

            <div class="flex gap-4">
                <button onclick="closeAIContentModal()"
                    class="px-6 py-3 text-gray-400 font-bold hover:text-gray-600 transition-all font-semibold">
                    Discard
                </button>
                <button onclick="insertAIContent()"
                    class="px-8 py-3 bg-brand-gradient text-white rounded-2xl font-bold shadow-lg shadow-purple-200 hover:shadow-purple-300 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-2 font-semibold">
                    <i class="fas fa-plus"></i>
                    Insert content
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    window.aiGeneratedContent = '';
    window.aiGeneratedExcerpt = '';
    window.currentAIAction = '';

    window.showAIContentModal = function(content, excerpt, action) {
        window.aiGeneratedContent = content;
        window.aiGeneratedExcerpt = excerpt || '';
        window.currentAIAction = action;
        document.getElementById('aiGeneratedContent').textContent = content;
        document.getElementById('aiContentModal').classList.remove('hidden');
        document.getElementById('aiContentModal').classList.add('flex');
    }

    window.closeAIContentModal = function() {
        document.getElementById('aiContentModal').classList.add('hidden');
        document.getElementById('aiContentModal').classList.remove('flex');
    }

    window.copyAIContent = function() {
        navigator.clipboard.writeText(window.aiGeneratedContent);
        // Show a brief success state
        if (event && event.currentTarget) {
            const copyBtn = event.currentTarget;
            const originalHtml = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
            setTimeout(() => copyBtn.innerHTML = originalHtml, 2000);
        }
    }

    window.insertAIContent = function() {
        const contentField = document.getElementById('content');
        const currentContent = contentField.value;
        const start = contentField.selectionStart;
        const end = contentField.selectionEnd;

        // If text was selected, replace it, otherwise append
        if (start !== end) {
            contentField.value = currentContent.substring(0, start) + window.aiGeneratedContent + currentContent
                .substring(end);
        } else {
            contentField.value = (currentContent ? currentContent + '\n\n' : '') + window.aiGeneratedContent;
        }

        window.closeAIContentModal();
        // Trigger preview if it's open
        const previewArea = document.getElementById('contentPreview');
        if (previewArea && !previewArea.classList.contains('hidden')) {
            // Re-render preview
            const previewToggle = document.getElementById('previewToggle');
            if (previewToggle) {
                previewToggle.click();
                previewToggle.click();
            }
        }
    }

    window.regenerateAIContent = function() {
        window.closeAIContentModal();
        document.getElementById('generateAIContent').click();
    }
</script>
