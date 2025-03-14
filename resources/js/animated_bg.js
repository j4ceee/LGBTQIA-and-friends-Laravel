// Get canvas and context
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');

const paths = {
    p1 : {
        speed: .8,
        shape: 4, // affects the wave pattern/length (lower = more peaks and valleys)
        amplitude: 40, // Maximum height of the wave from its center position
        fillStyle: '#5d5ccc',
    },
    p2 : {
        speed: 0.8,
        shape: 6,
        amplitude: 40,
        fillStyle: "#05acec",
    },
    p3 : {
        speed: .5,
        shape: 4,
        amplitude: 30,
        fillStyle: "#60c774",
    },
    p4 : {
        speed: .6,
        shape: 5,
        amplitude: 40,
        fillStyle: "#fecb1c",
    },
    p5 : {
        speed: .4,
        shape: 3,
        amplitude: 30,
        fillStyle: "#ff9101",
    },
    p6 : {
        speed: .4,
        shape: 3,
        amplitude: 1,
        fillStyle: "#eb3b42",
    }
}

// Configuration
const FPS_LIMIT = 30;
const INTERVAL = 1000 / FPS_LIMIT;
let lastTime = 0;

// Create a resolution scaling factor
let resolutionScale = .18;

let segments= 20; // higher = smoother

// Create lookup tables and pre-compute constants
let path_points = {};
let wave_height = 0;
const pathKeys = Object.keys(paths);
const reversedPathKeys = [...pathKeys].reverse();

let logicalWidth = window.innerWidth;
let logicalHeight = window.innerHeight;
let renderWidth, renderHeight;

// Resize handling
let resizeTimeout;
let isResizing = false;

// Mobile detection
const MOBILE_BREAKPOINT = 768; // Define mobile breakpoint
let isMobile = false;

// Function to check if device is mobile
function checkIfMobile() {
    return logicalWidth <= MOBILE_BREAKPOINT;
}

// Optimize segment count based on screen width
function getOptimalSegments(width) {
    if (width > 3000) return 14; // Even more optimized for 4K
    if (width > 2000) return 16;
    if (width > 1000) return 18;
    return 10; // Mobile optimization
}

// Set canvas size and create segments
function resizeCanvas() {
    // Set logical size
    logicalWidth = window.innerWidth;
    logicalHeight = window.innerHeight;

    // Update mobile status
    isMobile = checkIfMobile();

    // Set display size (css pixels)
    canvas.style.width = logicalWidth + 'px';
    canvas.style.height = logicalHeight + 'px';

    // Set actual size in memory (scaled for performance)
    renderWidth = Math.floor(logicalWidth * resolutionScale);
    renderHeight = Math.floor(logicalHeight * resolutionScale);

    // Only set isResizing flag if this isn't the initial resize on page load
    isResizing = true;

    // Update main canvas dimensions
    canvas.width = renderWidth;
    canvas.height = renderHeight;

    // Get optimal segment count for current screen width
    segments = getOptimalSegments(logicalWidth);

    // Recreate all segments
    createSegments();

    // A single immediate frame render to prevent the flash at the end
    if (isResizing) {
        // Run one immediate animation frame
        const now = performance.now();
        const time = now / 1000;
        updateWavePositions(time);
        renderFrame();
    }

    // Clear resize flag after a delay
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        isResizing = false;
    }, 150);
}

// Efficient resize handler
function handleResize() {
    // Only schedule a resize if we're not currently resizing
    if (!isResizing) {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(resizeCanvas, 100);
    }
}

window.addEventListener('resize', handleResize);
resizeCanvas();

// Create wave segments with optimized memory allocation
function createSegments() {
    wave_height = 0;
    path_points = {};

    // Pre-allocate points with proper sizing
    let pathIndex = pathKeys.length - 1;

    for (const pathKey of pathKeys) {
        path_points[pathKey] = [];
        const pathProps = paths[pathKey];

        // Calculate base height for this path
        const heightPercent = 16 * pathIndex;
        // Calculate height based on logical height (for responsive design)
        const logicalCalculatedHeight = (logicalHeight / 100) * heightPercent;
        // Scale the calculated height to render dimensions
        paths[pathKey].calculatedHeight = logicalCalculatedHeight * resolutionScale;

        // Calculate screen-responsive amplitude
        const baseAmplitude = pathProps.amplitude || 30;
        let logicalAmplitude;

        if (logicalHeight < 700) {
            logicalAmplitude = (logicalHeight / 100) * (baseAmplitude / 1080 * 100);
        } else {
            logicalAmplitude = baseAmplitude;
        }

        // Scale the amplitude to render dimensions
        paths[pathKey].scaledAmplitude = logicalAmplitude * resolutionScale;

        // Calculate screen-responsive shape
        const baseShape = pathProps.shape || 4;
        paths[pathKey].scaledShape = baseShape * (1920 / logicalWidth);

        // Create points efficiently
        const points = [];
        const segmentWidth = renderWidth / segments;

        for (let i = 0; i <= segments + 1; i++) {
            const x = segmentWidth * i;
            points.push({ x, y: wave_height, originalX: x });
        }

        // Add closing points
        points.push({ x: renderWidth + 5, y: renderHeight + 5 });
        points.push({ x: -5, y: renderHeight + 5 });

        path_points[pathKey] = points;
        pathIndex--;
    }
}

// Optimized path drawing function
function drawPath(points, fillStyle) {
    if (points.length < 2) return;

    ctx.beginPath();
    ctx.moveTo(points[0].x, points[0].y);

    // Batch the lineTo operations
    for (let i = 1; i < points.length; i++) {
        ctx.lineTo(points[i].x, points[i].y);
    }

    ctx.closePath();

    // Optimize fill operations
    if (fillStyle) {
        ctx.fillStyle = fillStyle;
        ctx.fill();
    }
}

// Update wave positions - with mobile optimization
function updateWavePositions(time) {
    // On mobile, only animate first and last waves
    if (isMobile && !isResizing) {
        const firstPathKey = pathKeys[0];  // p1
        const lastPathKey = pathKeys[pathKeys.length - 2];  // p5

        // Update only first and last waves
        updateWavePath(firstPathKey, time);
        updateWavePath(lastPathKey, time);
    } else {
        // On desktop or during resize, update all waves
        for (const pathKey of pathKeys) {
            updateWavePath(pathKey, time);
        }
    }
}

// Helper function to update a single wave path
function updateWavePath(pathKey, time) {
    const pathProps = paths[pathKey];
    const points = path_points[pathKey];
    const speed = pathProps.speed;
    const shape = pathProps.scaledShape;
    const amplitude = pathProps.scaledAmplitude;
    const height = pathProps.calculatedHeight;

    // Batch update all points in this path
    for (let i = 0; i <= segments; i++) {
        const sinus = Math.sin(time * speed + i / shape);
        points[i].y = sinus * amplitude + wave_height + height;
    }
}

// Render a single frame
function renderFrame() {
    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Draw all paths - back to front
    for (const pathKey of reversedPathKeys) {
        drawPath(path_points[pathKey], paths[pathKey].fillStyle);
    }
}

// Animation loop with significant optimizations
function animate(currentTime) {
    requestAnimationFrame(animate);

    // Skip animation frames during resize to reduce CPU load
    if (isResizing) {
        return;
    }

    // Throttle framerate
    if (currentTime - lastTime < INTERVAL) return;
    lastTime = currentTime;

    // Convert time to seconds
    const time = currentTime / 1000;

    // Update wave positions
    updateWavePositions(time);

    // Render the frame
    renderFrame();
}

// Start animation
requestAnimationFrame(animate);
