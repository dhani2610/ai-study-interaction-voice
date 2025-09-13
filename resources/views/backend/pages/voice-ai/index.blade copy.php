@extends('backend.layouts-new.app')

@section('title', 'Voice AI - Admin Panel')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg p-4 rounded-3">
        <h3 class="mb-3 text-center">🎤 Voice AI Assistant</h3>

        <div class="mb-3">
            <label class="fw-bold">Pertanyaan Murid (voice/text):</label>
            <textarea id="userText" class="form-control" rows="3" placeholder="Klik 🎙️ lalu bicara..."></textarea>
        </div>

        <div class="mb-3 text-center">
            <button id="recordBtn" class="btn btn-danger me-2">🎙️ Rekam</button>
            <button id="sendBtn" class="btn btn-primary">🚀 Kirim ke AI</button>
        </div>

        <div class="mb-3">
            <label class="fw-bold">Jawaban AI:</label>
            <textarea id="aiResponse" class="form-control" rows="3" readonly></textarea>
        </div>
    </div>
</div>

<script>
    const recordBtn = document.getElementById("recordBtn");
    const sendBtn = document.getElementById("sendBtn");
    const userText = document.getElementById("userText");
    const aiResponse = document.getElementById("aiResponse");

    // 🎙️ Setup SpeechRecognition
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognition = new SpeechRecognition();
    recognition.lang = "id-ID"; // ubah ke en-US kalau mau bahasa Inggris
    recognition.continuous = false;
    recognition.interimResults = false;

    let isRecording = false;

    recordBtn.addEventListener("click", () => {
        if (!isRecording) {
            recognition.start();
            isRecording = true;
            recordBtn.textContent = "⏹️ Stop";
        } else {
            recognition.stop();
            isRecording = false;
            recordBtn.textContent = "🎙️ Rekam";
        }
    });

    recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript;
        userText.value = transcript;
    };

    recognition.onerror = (event) => {
        console.error("Recognition error:", event.error);
        alert("⚠️ Tidak ada suara terdeteksi / error mic.");
        isRecording = false;
        recordBtn.textContent = "🎙️ Rekam";
    };

    recognition.onend = () => {
        isRecording = false;
        recordBtn.textContent = "🎙️ Rekam";
    };

    // 🚀 Kirim ke AI
    sendBtn.addEventListener("click", () => {
        const prompt = userText.value.trim();
        if (prompt === "") {
            alert("Silakan isi pertanyaan atau gunakan voice.");
            return;
        }

        aiResponse.value = "⏳ Sedang memproses...";

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
            // bersihkan markdown
            let cleanText = data.reply.replace(/\*\*/g, '').replace(/\*/g, '');

            aiResponse.value = cleanText;

            // 🔊 Convert jawaban AI ke suara
            window.speechSynthesis.cancel(); // clear suara sebelumnya
            const utterance = new SpeechSynthesisUtterance(cleanText);
            utterance.lang = "id-ID"; // ubah ke en-US jika AI balas bahasa Inggris
            utterance.rate = 1; // kecepatan normal
            utterance.pitch = 1; // nada normal
            window.speechSynthesis.speak(utterance);
        })
        .catch(err => {
            console.error(err);
            aiResponse.value = "⚠️ Error, coba lagi.";
        });
    });
</script>
@endsection
