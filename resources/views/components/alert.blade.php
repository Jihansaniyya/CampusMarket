@props(['type' => 'info', 'message' => ''])

<div
    {{ $attributes->merge(['class' => 'border px-4 py-3 rounded-lg mb-4 flex items-center justify-between animate-fade-in ' . $getColorClasses()]) }}>
    <span>
        <i class="fas {{ $getIcon() }} mr-2"></i>
        {{ $message ?: $slot }}
    </span>
    <button onclick="this.parentElement.remove()" class="hover:opacity-75">
        <i class="fas fa-times"></i>
    </button>
</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
</style>
