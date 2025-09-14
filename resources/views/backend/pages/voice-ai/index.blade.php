@extends('backend.layouts-new.app')

@section('title', 'Voice AI - Admin Panel')

@section('content')
    <div class=" mt-5">
        <div class="card shadow-lg p-4 rounded-4 border-0" style="background: #f9f9ff;">
            <h3 class="mb-4 text-center fw-bold text-primary">🎤 AI Materi</h3>
            <h3 class="mb-4 text-center fw-bold text-primary">{{ $article->judul }}</h3>

            <!-- Chat Area -->
            <div id="chatBox" class="mb-3 p-3 rounded-3"
                style="height: 350px; overflow-y: auto; background: #fff; border: 2px solid #eee;">
                <div class="text-muted text-center small">👉 Klik tombol 🎙️ untuk mulai bicara</div>
            </div>

            <!-- Record Button -->
            <div class="text-center">
                <button id="recordBtn" class="btn btn-lg px-5 py-3 rounded-pill fw-bold"
                    style="background: #ff4d6d; color: #fff; font-size: 1.2rem;">
                    🎙️ Mulai Rekam
                </button>
            </div>
        </div>
    </div>

    <script>
        const recordBtn = document.getElementById("recordBtn");
        const chatBox = document.getElementById("chatBox");

        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        const recognition = new SpeechRecognition();
        recognition.lang = "id-ID";
        recognition.continuous = true;
        recognition.interimResults = true;
        recognition.maxAlternatives = 1;

        let isRecording = false;
        let transcriptFinal = "";

        function addMessage(text, sender = "user") {
            const msg = document.createElement("div");
            msg.classList.add("p-2", "mb-2", "rounded-3");
            msg.style.maxWidth = "80%";

            if (sender === "user") {
                msg.classList.add("ms-auto", "text-end");
                msg.style.background = "#d1e7ff";
                msg.style.color = "#000";
            } else {
                msg.classList.add("me-auto", "text-start");
                msg.style.background = "#e6ffe6";
                msg.style.color = "#000";
            }

            msg.innerText = text;
            chatBox.appendChild(msg);
            chatBox.scrollTop = chatBox.scrollHeight;
            return msg;
        }

        // Tombol rekam
        recordBtn.addEventListener("click", () => {
            if (!isRecording) {
                transcriptFinal = "";
                recognition.start();
                isRecording = true;
                recordBtn.textContent = "⏹️ Berhenti Rekam";
                recordBtn.style.background = "#999";
            } else {
                recognition.stop();
                isRecording = false;
                recordBtn.textContent = "🎙️ Mulai Rekam";
                recordBtn.style.background = "#ff4d6d";

                const previewEl = document.getElementById("previewText");
                if (previewEl) previewEl.remove();

                if (transcriptFinal.trim() !== "") {
                    addMessage(transcriptFinal, "user");
                    kirimKeAI(transcriptFinal);
                }
            }
        });

        recognition.onresult = (event) => {
            let interimTranscript = "";
            for (let i = event.resultIndex; i < event.results.length; ++i) {
                if (event.results[i].isFinal) {
                    transcriptFinal = transcriptFinal.trim() + " " + event.results[i][0].transcript.trim();
                    transcriptFinal = transcriptFinal.replace(/\b(\w+)( \1\b)+/gi, "$1");
                } else {
                    interimTranscript = event.results[i][0].transcript;
                }
            }

            let previewEl = document.getElementById("previewText");
            if (!previewEl) {
                previewEl = document.createElement("div");
                previewEl.id = "previewText";
                previewEl.classList.add("p-2", "mb-2", "rounded-3", "ms-auto", "text-end");
                previewEl.style.maxWidth = "80%";
                previewEl.style.background = "#f0f0f0";
                previewEl.style.color = "#555";
                chatBox.appendChild(previewEl);
            }

            let previewText = (transcriptFinal + " " + interimTranscript).trim();
            previewText = previewText.replace(/\b(\w+)( \1\b)+/gi, "$1");

            previewEl.innerText = previewText;
            chatBox.scrollTop = chatBox.scrollHeight;
        };

        recognition.onend = () => {
            if (isRecording) {
                recognition.start();
            }
        };

        recognition.onerror = (event) => {
            console.error("Recognition error:", event.error);
            alert("⚠️ Error mic atau tidak ada suara.");
            isRecording = false;
            recordBtn.textContent = "🎙️ Mulai Rekam";
            recordBtn.style.background = "#ff4d6d";
        };

        // 🔊 Fungsi bicara multi bahasa
        function speakMultiLang(text) {
            window.speechSynthesis.cancel();

            // Bagi per baris atau titik
            const parts = text.split(/\n|\. /);

            for (let part of parts) {
                if (!part.trim()) continue;
                const utterance = new SpeechSynthesisUtterance(part.trim());

                // Deteksi kasar: kalau mayoritas hurufnya A-Z → English
                if (/^[a-zA-Z\s]+$/.test(part.trim())) {
                    utterance.lang = "en-US";
                } else {
                    utterance.lang = "id-ID";
                }

                utterance.rate = 1;
                utterance.pitch = 1;
                window.speechSynthesis.speak(utterance);
            }
        }

        // Kirim prompt ke AI
        function kirimKeAI(prompt) {
            const aiBubble = addMessage("⏳ Sedang diproses...", "ai");

            fetch("{{ url('/voice-ai/' . $article->id) }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        prompt
                    })
                })
                .then(res => res.json())
                .then(data => {
                    let cleanText = data.reply.replace(/\*\*/g, '').replace(/\*/g, '');
                    aiBubble.innerText = cleanText;

                    // 🔊 Baca jawaban AI dengan multi bahasa
                    speakMultiLang(cleanText);

                    chatBox.scrollTop = chatBox.scrollHeight;
                })
                .catch(err => {
                    console.error(err);
                    aiBubble.innerText = "⚠️ Error, coba lagi.";
                });
        }
    </script>
@endsection
