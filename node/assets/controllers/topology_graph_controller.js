import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static values = {
        nodes: Array
    }

    connect() {
        console.log("TopologyGraph controller connected")
        console.log("Initial nodes value:", this.nodesValue)
        console.log("Has nodes value?", this.hasNodesValue)
        
        // Wait for DOM to be ready and container to have dimensions
        setTimeout(() => {
            this.calculatePositions()
            this.renderNodes()
        }, 100)
    }

    nodesValueChanged() {
        setTimeout(() => {
            this.calculatePositions()
            this.renderNodes()
        }, 100)
    }

    calculatePositions() {
        if (!this.nodesValue || this.nodesValue.length === 0) return

        // Get actual container dimensions
        const containerWidth = this.element.offsetWidth
        const containerHeight = this.element.offsetHeight
        
        // Calculate center of the container
        const centerX = containerWidth / 2
        const centerY = containerHeight / 2
        
        // Calculate radius to match the desired layout (like img.png)
        const containerRadius = Math.min(containerWidth, containerHeight) / 2
        const radius = containerRadius - 100  // Leave 100px margin from edges
        const nodeCount = this.nodesValue.length

        console.log('Container dimensions:', containerWidth, 'x', containerHeight)
        console.log('Center:', centerX, centerY, 'Radius:', radius)
        console.log('Number of nodes:', nodeCount)

        // Create a new array with calculated positions (don't modify the original)
        this.positionedNodes = this.nodesValue.map((node, index) => {
            const angle = (index / nodeCount) * 2 * Math.PI
            // Calculate the position where the node will be rendered (top-left corner)
            const renderX = Math.round(centerX + (radius * Math.cos(angle) + 25) )
            const renderY = Math.round(centerY + (radius * Math.sin(angle) + 25) )
            // The actual center of the node is at renderX + 25, renderY + 25
            const centerNodeX = renderX
            const centerNodeY = renderY
            console.log(`Node ${index} (${node.name}): angle=${angle.toFixed(2)}, renderPos=(${renderX}, ${renderY}), center=(${centerNodeX}, ${centerNodeY})`)
            
            return {
                ...node,
                x: renderX +25 ,  // Store the actual center position
                y: renderY +25
            }
        })

        // Calculate the actual center of the node circle for better centering
        let avgX = 0, avgY = 0
        this.positionedNodes.forEach(node => {
            avgX += node.x
            avgY += node.y
        })
        const visualCenterX = avgX / this.positionedNodes.length
        const visualCenterY = avgY / this.positionedNodes.length
        
        // Update center hub position using the visual center of nodes
        const centralHub = this.element.querySelector('.central-hub')
        if (centralHub) {
            centralHub.style.setProperty('position', 'absolute', 'important')
            centralHub.style.setProperty('left', `${visualCenterX}px`, 'important')
            centralHub.style.setProperty('top', `${visualCenterY}px`, 'important')
            centralHub.style.setProperty('transform', 'translate(-50%, -50%)', 'important')
            centralHub.style.setProperty('margin', '0', 'important')
            console.log(`Centering hub at visual center (${visualCenterX}, ${visualCenterY}) vs container center (${centerX}, ${centerY})`)
        }
    }

    renderNodes() {
        console.log('renderNodes called with:', this.positionedNodes)
        
        // Clear existing nodes (except central hub and button)
        const existingNodes = this.element.querySelectorAll('.network-node')
        existingNodes.forEach(node => node.remove())

        if (!this.positionedNodes || this.positionedNodes.length === 0) {
            console.log('No positioned nodes to render')
            return
        }

        // Render new nodes  
        this.positionedNodes.forEach((node, index) => {
            const nodeElement = document.createElement('div')
            nodeElement.className = `network-node ${node.status}`
            nodeElement.style.left = `${node.x - 40}px`  // Node is 80px wide, so center is at -40px
            nodeElement.style.top = `${node.y - 40}px`   // Node is 80px high, so center is at -40px
            nodeElement.style.position = 'absolute'
            nodeElement.style.zIndex = '5'
            
            nodeElement.innerHTML = `
                <div class="node-label">${node.name}</div>
                <div class="node-status">${node.statusLabel}</div>
            `
            
            console.log(`Rendering node ${index}: ${node.name} at position (${node.x }, ${node.y })`)
            this.element.appendChild(nodeElement)
        })

        console.log(`Rendered ${this.positionedNodes.length} nodes`)
        this.renderConnectionLines()
    }

    renderConnectionLines() {
        // Clear existing connection lines
        const existingLines = this.element.querySelectorAll('.connection-line')
        existingLines.forEach(line => line.remove())

        if (!this.positionedNodes || this.positionedNodes.length === 0) return

        // Get the actual position of the central hub (CORE)
        const centralHub = this.element.querySelector('.central-hub')
        if (!centralHub) return

        // Get the actual center position of the CORE hub
        const hubRect = centralHub.getBoundingClientRect()
        const containerRect = this.element.getBoundingClientRect()
        const coreX = hubRect.left - containerRect.left + hubRect.width / 2
        const coreY = hubRect.top - containerRect.top + hubRect.height / 2

        // Create connection lines from each node center to core center
        this.positionedNodes.forEach((node, index) => {
            const lineElement = document.createElement('div')
            lineElement.className = 'connection-line'
            lineElement.style.position = 'absolute'
            lineElement.style.height = '2px'
            lineElement.style.transformOrigin = '0 50%'
            lineElement.style.zIndex = '1'
            
            // The rendered node is positioned at (node.x - 25, node.y - 25) but centered with transform
            // So the actual center of the node is at (node.x - 25 + 25, node.y - 25 + 25) = (node.x, node.y)
            // But we need to position the line to start from this center
            const nodeCenterX = node.x
            const nodeCenterY = node.y
            
            // Calculate distance and angle from this node center to core center
            const deltaX = coreX - nodeCenterX
            const deltaY = coreY - nodeCenterY
            const distance = Math.sqrt(deltaX * deltaX + deltaY * deltaY)
            const angle = Math.atan2(deltaY, deltaX)
            
            // Position the line to start exactly at the node's center
            lineElement.style.left = `${nodeCenterX}px`
            lineElement.style.top = `${nodeCenterY}px`
            lineElement.style.width = `${distance}px`
            lineElement.style.transform = `translate(0, -50%) rotate(${angle}rad)`
            
            // Randomly make some lines active
            if (Math.random() > 0.5) {
                lineElement.classList.add('active')
            }
            
            console.log(`Line ${index}: from node center (${nodeCenterX}, ${nodeCenterY}) to core center (${coreX}, ${coreY}): distance=${distance.toFixed(1)}, angle=${angle.toFixed(2)}rad`)
            this.element.appendChild(lineElement)
        })
    }
}