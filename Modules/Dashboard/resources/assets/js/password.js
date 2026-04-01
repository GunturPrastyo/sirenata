const STRENGTH_LEVELS = [{
        max: 1,
        label: '-',
        color: 'bg-pink-500',
        width: '0%'
    },
    {
        max: 2,
        label: 'Lemah',
        color: 'bg-red-500',
        width: '25%'
    },
    {
        max: 3,
        label: 'Sedang',
        color: 'bg-yellow-500',
        width: '50%'
    },
    {
        max: 4,
        label: 'Kuat',
        color: 'bg-blue-500',
        width: '75%'
    },
    {
        max: 5,
        label: 'Sangat Kuat',
        color: 'bg-green-500',
        width: '100%'
    },
];

const SVG_EYE_OPEN = `
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
        9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;

const SVG_EYE_CLOSED = `
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
        a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243
        M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29
        M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943
        9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;

function togglePassword(inputId, event) {
    const input = document.getElementById(inputId);
    const svg = event.currentTarget.querySelector('svg');
    const isHidden = input.type === 'password';

    input.type = isHidden ? 'text' : 'password';
    svg.innerHTML = isHidden ? SVG_EYE_CLOSED : SVG_EYE_OPEN;
}

function validatePassword() {
    const password = document.getElementById('password').value;

    const checks = {
        'req-length': password.length >= 8,
        'req-lowercase': /[a-z]/.test(password),
        'req-uppercase': /[A-Z]/.test(password),
        'req-number': /\d/.test(password),
        'req-symbol': /[^A-Za-z0-9]/.test(password),
    };

    Object.entries(checks).forEach(([id, passed]) => updateRequirement(id, passed));

    const score = Object.values(checks).filter(Boolean).length;
    updateStrengthMeter(score);
    validatePasswordMatch();

    return Object.values(checks).every(Boolean);
}

function validatePasswordMatch() {
    const password = document.getElementById('password').value;
    const repassword = document.getElementById('repassword');
    const isMatch = repassword.value.length > 0 && password === repassword.value;
    const isEmpty = repassword.value.length === 0;

    updateRequirement('req-match', isMatch);
    repassword.classList.toggle('border-red-400', !isMatch && !isEmpty);
    repassword.classList.toggle('border-green-400', isMatch);
    repassword.classList.toggle('border-gray-300', isEmpty);

    return isMatch || isEmpty;
}

function updateStrengthMeter(score) {
    const meter = document.getElementById('strengthMeter');
    const label = document.getElementById('strengthLabel');
    const level = STRENGTH_LEVELS.find(l => score <= l.max) ?? STRENGTH_LEVELS.at(-1);

    meter.style.width = level.width;
    meter.className = `h-full transition-all duration-300 rounded-full ${level.color}`;
    label.textContent = level.label;
}

function updateRequirement(id, isValid) {
    const el = document.getElementById(id);

    el.classList.toggle('text-green-500', isValid);
    el.classList.toggle('text-gray-500', !isValid);

    el.querySelector('svg').innerHTML = isValid ?
        `<circle cx="10" cy="10" r="10" fill="currentColor"/>
        <path d="M6 10L9 13L14 8" stroke="white" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round" fill="none"/>` :
        `<circle cx="10" cy="10" r="10" opacity="0.3"/>`;
}


window.togglePassword = togglePassword;
window.validatePassword = validatePassword;
window.validatePasswordMatch = validatePasswordMatch;