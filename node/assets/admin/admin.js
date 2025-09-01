import './css/admin.css'
import './css/menu.css'
import '../bootstrap.js'

// Add some interactive effects
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
    });
});

// Simulate real-time data updates
setInterval(() => {
    const cpuValue = Math.random() * 100;
    const memValue = Math.random() * 16;
    document.querySelector('.metric-value').textContent = cpuValue.toFixed(1) + '%';
    document.querySelector('.progress-fill').style.width = cpuValue + '%';
}, 3000);

// Add terminal typing effect
const terminal = document.querySelector('.terminal');
const commands = [
    'Scanning neural pathways...',
    'Analyzing data streams...',
    'Quantum encryption verified.',
    'System integrity: OPTIMAL'
];

let commandIndex = 0;
setInterval(() => {
    if (commandIndex < commands.length) {
        const newLine = document.createElement('div');
        newLine.className = 'terminal-line';
        newLine.innerHTML = `<span class="terminal-prompt">sys@matrix:~$</span> ${commands[commandIndex]}`;
        terminal.insertBefore(newLine, terminal.lastElementChild);
        terminal.scrollTop = terminal.scrollHeight;
        commandIndex++;
    }
}, 4000);