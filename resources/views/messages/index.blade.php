@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1 class="text-center">Comunidad</h1>

        <div class="card shadow-sm">
            <div class="card-body" style="background-color: #f9f9f9;">
                <div id="chat" class="p-3" style="height: 400px; overflow-y: scroll; border-radius: 8px; border: 1px solid #ddd;">
                    @foreach($messages as $message)
                        <div class="mb-3 p-2 rounded" style="background-color: #e0f7fa;" id="message-{{ $message->id }}">
                            <strong class="text-primary">{{ $message->user->name }}:</strong>
                            <p class="mb-1">{{ $message->message }}</p>
                            <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <form action="{{ route('messages.store') }}" method="POST" id="message-form" class="mt-4">
            @csrf
            <input type="hidden" name="reply_to" id="reply-to">
            <div class="input-group">
                <input type="text" name="message" id="message-input" class="form-control" placeholder="Escribe un mensaje..." required>
                <button type="submit" class="btn btn-success">Enviar</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatContainer = document.getElementById('chat');
            chatContainer.scrollTop = chatContainer.scrollHeight; // Scroll to bottom

            const messageInput = document.getElementById('message-input');
            const replyToInput = document.getElementById('reply-to');
            const replyButtons = document.querySelectorAll('.reply-button');

            replyButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const messageId = button.getAttribute('data-message-id');
                    replyToInput.value = messageId;
                    messageInput.focus();
                });
            });
        });
    </script>
@endsection
