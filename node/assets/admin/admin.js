import '../bootstrap.js'
import './css/admin.css'
import './css/menu.css'

console.log("ADMIN")

document.addEventListener('turbo:before-visit', (event) => {
    // Find the nav item that contains the clicked link
    const clickedLink = document.querySelector(`a[href="${event.detail.url}"]`);
    if (clickedLink) {
        const navItem = clickedLink.closest('.nav-item');
        if (navItem) {
            document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
            navItem.classList.add('active');
        }
    }
});

// Store interval IDs for cleanup
let metricsInterval;
let terminalInterval;

function initializeMetrics() {
    // Clear existing intervals
    if (metricsInterval) clearInterval(metricsInterval);
    if (terminalInterval) clearInterval(terminalInterval);

    // Simulate real-time data updates
    const metricValue = document.querySelector('.metric-value');
    const progressFill = document.querySelector('.progress-fill');

    if (metricValue && progressFill) {
        metricsInterval = setInterval(() => {
            const cpuValue = Math.random() * 100;
            metricValue.textContent = cpuValue.toFixed(1) + '%';
            progressFill.style.width = cpuValue + '%';
        }, 3000);
    }

    // Add terminal typing effect
    const terminal = document.querySelector('.terminal');
    if (terminal) {
        const commands = [
            'Scanning neural pathways...',
            'Analyzing data streams...',
            'Quantum encryption verified.',
            'System integrity: OPTIMAL'
        ];

        let commandIndex = 0;
        terminalInterval = setInterval(() => {
            if (commandIndex < commands.length && terminal) {
                const newLine = document.createElement('div');
                newLine.className = 'terminal-line';
                newLine.innerHTML = `<span class="terminal-prompt">sys@matrix:~$</span> ${commands[commandIndex]}`;
                terminal.insertBefore(newLine, terminal.lastElementChild);
                terminal.scrollTop = terminal.scrollHeight;
                commandIndex++;
            }
        }, 4000);
    }
}

// Initialize on page load and after Turbo navigation
document.addEventListener('turbo:load', initializeMetrics);
document.addEventListener('DOMContentLoaded', initializeMetrics);