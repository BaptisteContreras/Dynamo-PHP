import '../../bootstrap.js'
import '../css/menu.css'
import '../css/topology.css'

console.log("TOPOLOGY")
class SimpleTopology {
    constructor() {
        this.nodes = [];
        this.connections = [];
        this.init();
    }

    init() {
        this.createNodes();
        this.createConnections();
        this.startAnimations();
    }

    createNodes() {
        const topology = document.getElementById('topology');
        const centerX = 350;
        const centerY = 350;
        const radius = 200;

        // Simple node configuration
        const nodes = [
            { name: 'WEB-01', status: 'up' },
            { name: 'WEB-02', status: 'pending' },
            { name: 'DB-01', status: 'up' },
            { name: 'API-01', status: 'error' },
            { name: 'CACHE', status: 'up' },
            { name: 'LB-01', status: 'pending' },
            { name: 'FW-01', status: 'up' },
            { name: 'MON-01', status: 'error' }
        ];

        nodes.forEach((config, index) => {
            const angle = (index * 360 / nodes.length * Math.PI) / 180;
            const x = centerX + radius * Math.cos(angle);
            const y = centerY + radius * Math.sin(angle);

            this.createNode(topology, config.name, config.status, x, y);
        });

        this.updateStatusCounts();
    }

    createNode(container, name, status, x, y) {
        const node = document.createElement('div');
        node.className = `network-node status-${status}`;
        node.style.left = (x - 40) + 'px';
        node.style.top = (y - 40) + 'px';

        const statusText = {
            'up': 'ONLINE',
            'pending': 'PENDING',
            'error': 'ERROR'
        };

        node.innerHTML = `
                    <div class="node-label">${name}</div>
                    <div class="node-status">${statusText[status]}</div>
                `;

        // Add event listeners
        node.addEventListener('mouseenter', (e) => this.showNodeInfo(e, name, status));
        node.addEventListener('mouseleave', () => this.hideNodeInfo());

        container.appendChild(node);

        this.nodes.push({
            element: node,
            name: name,
            status: status,
            x: x,
            y: y,
            load: Math.floor(Math.random() * 100),
            ping: Math.floor(Math.random() * 50) + 1
        });
    }

    createConnections() {
        const topology = document.getElementById('topology');
        const centerX = 350;
        const centerY = 350;

        // Connect each node ONLY to the central hub (star topology)
        this.nodes.forEach(node => {
            this.createConnection(topology, centerX, centerY, node.x, node.y);
        });
    }

    createConnection(container, x1, y1, x2, y2) {
        const line = document.createElement('div');
        line.className = 'connection-line';

        const length = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
        const angle = Math.atan2(y2 - y1, x2 - x1) * 180 / Math.PI;

        line.style.width = length + 'px';
        line.style.left = x1 + 'px';
        line.style.top = y1 + 'px';
        line.style.transform = `rotate(${angle}deg)`;

        if (Math.random() > 0.5) {
            line.classList.add('active');
        }

        container.appendChild(line);
        this.connections.push(line);
    }

    showNodeInfo(event, name, status) {
        const nodeInfo = document.getElementById('nodeInfo');
        const node = this.nodes.find(n => n.name === name);

        nodeInfo.style.left = (event.pageX + 20) + 'px';
        nodeInfo.style.top = (event.pageY - 50) + 'px';

        document.getElementById('nodeTitle').textContent = name;
        document.getElementById('nodeStatus').textContent = status.toUpperCase();
        document.getElementById('nodeLoad').textContent = node.load + '%';
        document.getElementById('nodePing').textContent = node.ping + 'ms';

        nodeInfo.classList.add('show');
    }

    hideNodeInfo() {
        document.getElementById('nodeInfo').classList.remove('show');
    }

    updateStatusCounts() {
        const upCount = this.nodes.filter(n => n.status === 'up').length;
        const pendingCount = this.nodes.filter(n => n.status === 'pending').length;
        const errorCount = this.nodes.filter(n => n.status === 'error').length;

        document.getElementById('upCount').textContent = upCount;
        document.getElementById('pendingCount').textContent = pendingCount;
        document.getElementById('errorCount').textContent = errorCount;
    }

    refreshNodes() {
        this.nodes.forEach(node => {
            const statuses = ['up', 'pending', 'error'];
            const weights = [0.6, 0.25, 0.15];

            let random = Math.random();
            let newStatus = 'up';

            if (random < weights[2]) {
                newStatus = 'error';
            } else if (random < weights[2] + weights[1]) {
                newStatus = 'pending';
            }

            node.status = newStatus;
            node.element.className = `network-node status-${newStatus}`;

            const statusText = {
                'up': 'ONLINE',
                'pending': 'PENDING',
                'error': 'ERROR'
            };

            node.element.querySelector('.node-status').textContent = statusText[newStatus];
        });

        this.updateStatusCounts();
    }

    startAnimations() {
        // Animate connections
        setInterval(() => {
            this.connections.forEach(connection => {
                if (Math.random() > 0.7) {
                    connection.classList.toggle('active');
                }
            });
        }, 2000);

        // Auto refresh nodes occasionally
        setInterval(() => {
            if (Math.random() > 0.8) {
                const randomNode = this.nodes[Math.floor(Math.random() * this.nodes.length)];
                this.changeNodeStatus(randomNode);
            }
        }, 5000);
    }

    changeNodeStatus(node) {
        const statuses = ['up', 'pending', 'error'];
        const currentIndex = statuses.indexOf(node.status);
        const newStatus = statuses[(currentIndex + 1) % statuses.length];

        node.status = newStatus;
        node.element.className = `network-node status-${newStatus}`;

        const statusText = {
            'up': 'ONLINE',
            'pending': 'PENDING',
            'error': 'ERROR'
        };

        node.element.querySelector('.node-status').textContent = statusText[newStatus];
        this.updateStatusCounts();
    }
}

// Global function for button
function refreshTopology() {
    window.topology.refreshNodes();
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    window.topology = new SimpleTopology();
});