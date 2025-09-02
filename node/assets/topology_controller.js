// import { Controller } from '@hotwired/stimulus'
//
// export default class extends Controller {
//     static targets = ['container', 'nodeInfo', 'upCount', 'pendingCount', 'errorCount', 'nodeTitle', 'nodeStatus', 'nodeLoad', 'nodePing']
//
//     connect() {
//         this.nodes = []
//         this.connections = []
//         this.init()
//     }
//
//     disconnect() {
//         this.stopAnimations()
//     }
//
//     init() {
//         this.createNodes()
//         this.createConnections()
//         this.startAnimations()
//     }
//
//     createNodes() {
//         const centerX = 350
//         const centerY = 350
//         const radius = 200
//
//         const nodes = [
//             { name: 'WEB-01', status: 'up' },
//             { name: 'WEB-02', status: 'pending' },
//             { name: 'DB-01', status: 'up' },
//             { name: 'API-01', status: 'error' },
//             { name: 'CACHE', status: 'up' },
//             { name: 'LB-01', status: 'pending' },
//             { name: 'FW-01', status: 'up' },
//             { name: 'MON-01', status: 'error' }
//         ]
//
//         nodes.forEach((config, index) => {
//             const angle = (index * 360 / nodes.length * Math.PI) / 180
//             const x = centerX + radius * Math.cos(angle)
//             const y = centerY + radius * Math.sin(angle)
//
//             this.createNode(config.name, config.status, x, y)
//         })
//
//         this.updateStatusCounts()
//     }
//
//     createNode(name, status, x, y) {
//         const node = document.createElement('div')
//         node.className = `network-node status-${status}`
//         node.style.left = (x - 40) + 'px'
//         node.style.top = (y - 40) + 'px'
//
//         const statusText = {
//             'up': 'ONLINE',
//             'pending': 'PENDING',
//             'error': 'ERROR'
//         }
//
//         node.innerHTML = `
//             <div class="node-label">${name}</div>
//             <div class="node-status">${statusText[status]}</div>
//         `
//
//         node.addEventListener('mouseenter', (e) => this.showNodeInfo(e, name, status))
//         node.addEventListener('mouseleave', () => this.hideNodeInfo())
//
//         this.containerTarget.appendChild(node)
//
//         this.nodes.push({
//             element: node,
//             name: name,
//             status: status,
//             x: x,
//             y: y,
//             load: Math.floor(Math.random() * 100),
//             ping: Math.floor(Math.random() * 50) + 1
//         })
//     }
//
//     createConnections() {
//         const centerX = 350
//         const centerY = 350
//
//         this.nodes.forEach(node => {
//             this.createConnection(this.containerTarget, centerX, centerY, node.x, node.y)
//         })
//     }
//
//     createConnection(container, x1, y1, x2, y2) {
//         const line = document.createElement('div')
//         line.className = 'connection-line'
//
//         const length = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2))
//         const angle = Math.atan2(y2 - y1, x2 - x1) * 180 / Math.PI
//
//         line.style.width = length + 'px'
//         line.style.left = x1 + 'px'
//         line.style.top = y1 + 'px'
//         line.style.transform = `rotate(${angle}deg)`
//
//         if (Math.random() > 0.5) {
//             line.classList.add('active')
//         }
//
//         container.appendChild(line)
//         this.connections.push(line)
//     }
//
//     showNodeInfo(event, name, status) {
//         const node = this.nodes.find(n => n.name === name)
//
//         this.nodeInfoTarget.style.left = (event.pageX + 20) + 'px'
//         this.nodeInfoTarget.style.top = (event.pageY - 50) + 'px'
//
//         this.nodeTitleTarget.textContent = name
//         this.nodeStatusTarget.textContent = status.toUpperCase()
//         this.nodeLoadTarget.textContent = node.load + '%'
//         this.nodePingTarget.textContent = node.ping + 'ms'
//
//         this.nodeInfoTarget.classList.add('show')
//     }
//
//     hideNodeInfo() {
//         this.nodeInfoTarget.classList.remove('show')
//     }
//
//     updateStatusCounts() {
//         const upCount = this.nodes.filter(n => n.status === 'up').length
//         const pendingCount = this.nodes.filter(n => n.status === 'pending').length
//         const errorCount = this.nodes.filter(n => n.status === 'error').length
//
//         if (this.hasUpCountTarget) this.upCountTarget.textContent = upCount
//         if (this.hasPendingCountTarget) this.pendingCountTarget.textContent = pendingCount
//         if (this.hasErrorCountTarget) this.errorCountTarget.textContent = errorCount
//     }
//
//     refresh() {
//         this.nodes.forEach(node => {
//             const statuses = ['up', 'pending', 'error']
//             const weights = [0.6, 0.25, 0.15]
//
//             let random = Math.random()
//             let newStatus = 'up'
//
//             if (random < weights[2]) {
//                 newStatus = 'error'
//             } else if (random < weights[2] + weights[1]) {
//                 newStatus = 'pending'
//             }
//
//             node.status = newStatus
//             node.element.className = `network-node status-${newStatus}`
//
//             const statusText = {
//                 'up': 'ONLINE',
//                 'pending': 'PENDING',
//                 'error': 'ERROR'
//             }
//
//             node.element.querySelector('.node-status').textContent = statusText[newStatus]
//         })
//
//         this.updateStatusCounts()
//     }
//
//     startAnimations() {
//         this.connectionInterval = setInterval(() => {
//             this.connections.forEach(connection => {
//                 if (Math.random() > 0.7) {
//                     connection.classList.toggle('active')
//                 }
//             })
//         }, 2000)
//
//         this.nodeUpdateInterval = setInterval(() => {
//             if (Math.random() > 0.8) {
//                 const randomNode = this.nodes[Math.floor(Math.random() * this.nodes.length)]
//                 this.changeNodeStatus(randomNode)
//             }
//         }, 5000)
//     }
//
//     stopAnimations() {
//         if (this.connectionInterval) {
//             clearInterval(this.connectionInterval)
//         }
//         if (this.nodeUpdateInterval) {
//             clearInterval(this.nodeUpdateInterval)
//         }
//     }
//
//     changeNodeStatus(node) {
//         const statuses = ['up', 'pending', 'error']
//         const currentIndex = statuses.indexOf(node.status)
//         const newStatus = statuses[(currentIndex + 1) % statuses.length]
//
//         node.status = newStatus
//         node.element.className = `network-node status-${newStatus}`
//
//         const statusText = {
//             'up': 'ONLINE',
//             'pending': 'PENDING',
//             'error': 'ERROR'
//         }
//
//         node.element.querySelector('.node-status').textContent = statusText[newStatus]
//         this.updateStatusCounts()
//     }
// }