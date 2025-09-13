@extends('backend.layouts-new.app')

@section('title', 'Voice AI - Admin Panel')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4 rounded-4 border-0" style="background: #f9f9ff;">
        <h3 class="mb-4 text-center fw-bold text-primary">🎤 Voice AI Assistant</h3>
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
    recognition.continuous = true;     // terus rekam
    recognition.interimResults = true; // tampilkan live preview
    recognition.maxAlternatives = 1;

    let isRecording = false;
    let transcriptFinal = "";

    // Tambah bubble ke chat
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
            recordBtn.textContent = "⏹️ Stop Rekam";
            recordBtn.style.background = "#999";
        } else {
            recognition.stop();
            isRecording = false;
            recordBtn.textContent = "🎙️ Mulai Rekam";
            recordBtn.style.background = "#ff4d6d";

            // Hapus bubble preview
            const previewEl = document.getElementById("previewText");
            if (previewEl) previewEl.remove();

            // Kalau ada hasil rekaman, kirim ke AI
            if (transcriptFinal.trim() !== "") {
                addMessage(transcriptFinal, "user");
                kirimKeAI(transcriptFinal);
            }
        }
    });

    // Tampilkan hasil rekaman realtime
    recognition.onresult = (event) => {
        let interimTranscript = "";
        for (let i = event.resultIndex; i < event.results.length; ++i) {
            if (event.results[i].isFinal) {
                transcriptFinal += event.results[i][0].transcript + " ";
            } else {
                interimTranscript += event.results[i][0].transcript;
            }
        }

        // Preview teks sementara
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
        previewEl.innerText = transcriptFinal + interimTranscript;
        chatBox.scrollTop = chatBox.scrollHeight;
    };

    // Restart otomatis kalau stop sendiri
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

    // Kirim prompt ke AI
    function kirimKeAI(prompt) {
        const aiBubble = addMessage("⏳ Sedang diproses...", "ai");

        fetch("{{ route('ai-voice') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ prompt })
        })
        .then(res => res.json())
        .then(data => {
            let cleanText = data.reply.replace(/\*\*/g, '').replace(/\*/g, '');
            aiBubble.innerText = cleanText;

            // 🔊 Ucapkan jawaban AI
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(cleanText);
            utterance.lang = "id-ID";
            utterance.rate = 1;
            utterance.pitch = 1;
            window.speechSynthesis.speak(utterance);

            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(err => {
            console.error(err);
            aiBubble.innerText = "⚠️ Error, coba lagi.";
        });
    }
</script>
@endsection
