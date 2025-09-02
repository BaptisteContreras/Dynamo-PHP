import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['metricValue', 'progressFill', 'terminal']

    connect() {
        this.initializeMetrics()
    }

    disconnect() {
        this.stopMetrics()
    }

    initializeMetrics() {
        this.stopMetrics()

        // Simulate real-time data updates
        if (this.hasMetricValueTarget && this.hasProgressFillTarget) {
            this.metricsInterval = setInterval(() => {
                const cpuValue = Math.random() * 100
                this.metricValueTarget.textContent = cpuValue.toFixed(1) + '%'
                this.progressFillTarget.style.width = cpuValue + '%'
            }, 3000)
        }

        // Add terminal typing effect
        if (this.hasTerminalTarget) {
            const commands = [
                'Scanning neural pathways...',
                'Analyzing data streams...',
                'Quantum encryption verified.',
                'System integrity: OPTIMAL'
            ]

            let commandIndex = 0
            this.terminalInterval = setInterval(() => {
                if (commandIndex < commands.length) {
                    const newLine = document.createElement('div')
                    newLine.className = 'terminal-line'
                    newLine.innerHTML = `<span class="terminal-prompt">sys@matrix:~$</span> ${commands[commandIndex]}`
                    this.terminalTarget.insertBefore(newLine, this.terminalTarget.lastElementChild)
                    this.terminalTarget.scrollTop = this.terminalTarget.scrollHeight
                    commandIndex++
                }
            }, 4000)
        }
    }

    stopMetrics() {
        if (this.metricsInterval) {
            clearInterval(this.metricsInterval)
        }
        if (this.terminalInterval) {
            clearInterval(this.terminalInterval)
        }
    }
}