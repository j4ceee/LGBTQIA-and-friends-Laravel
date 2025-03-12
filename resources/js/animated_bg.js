// Get canvas and context
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');

const paths = {
    p1 : {
        speed: .8,
        shape: 4, // affects the wave pattern/length (lower = more peaks and valleys)
        amplitude: 40, // Maximum height of the wave from its center position
        fillStyle: '#5d5ccc',
        /* stroke: "#5d5ccc",
        shadow: {
            color: "rgba(0,0,0,1)",
            blur: 5,
            offsetX: 0,
            offsetY: 0,
        } */
    },
    p2 : {
        speed: 0.8,
        shape: 6,
        amplitude: 40,
        fillStyle: "#05acec",
        /* stroke: "#05acec",
        shadow: {
            color: "rgba(0,0,0,1)",
            blur: 5,
            offsetX: 0,
            offsetY: 0,
        } */
    },
    p3 : {
        speed: .5,
        shape: 4,
        amplitude: 30,
        fillStyle: "#60c774",
        /* stroke: "#60c774",
        shadow: {
            color: "rgba(0,0,0,1)",
            blur: 5,
            offsetX: 0,
            offsetY: 0,
        } */
    },
    p4 : {
        speed: .6,
        shape: 5,
        amplitude: 40,
        fillStyle: "#fecb1c",
        /* stroke: "#fecb1c",
        shadow: {
            color: "rgba(0,0,0,1)",
            blur: 5,
            offsetX: 0,
            offsetY: 0,
        } */
    },
    p5 : {
        speed: .4,
        shape: 3,
        amplitude: 30,
        fillStyle: "#ff9101",
        /* stroke: "#ff9101",
        shadow: {
            color: "rgba(0,0,0,1)",
            blur: 5,
            offsetX: 0,
            offsetY: 0,
        } */
    },
    p6 : {
        speed: .4,
        shape: 3,
        amplitude: 1,
        fillStyle: "#eb3b42",
        /* stroke: "#eb3b42",
        shadow: {
            color: "rgba(0,0,0,1)",
            blur: 5,
            offsetX: 0,
            offsetY: 0,
        } */
    }
}

// Configuration
const FPS_LIMIT = 60;
const INTERVAL = 1000 / FPS_LIMIT;
let lastTime = 0;

// Create a resolution scaling factor
let resolutionScale = .2;

let segments= 20; // higher = smoother

// Create lookup tables and pre-compute constants
let path_points = {};
let wave_height = 0;
const pathKeys = Object.keys(paths);
const reversedPathKeys = [...pathKeys].reverse();

let logicalWidth = window.innerWidth;
let logicalHeight = window.innerHeight;
let renderWidth, renderHeight;

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

    // Set display size (css pixels)
    canvas.style.width = logicalWidth + 'px';
    canvas.style.height = logicalHeight + 'px';

    // Set actual size in memory (scaled for performance)
    renderWidth = Math.floor(logicalWidth * resolutionScale);
    renderHeight = Math.floor(logicalHeight * resolutionScale);
    canvas.width = renderWidth;
    canvas.height = renderHeight;

    // Get optimal segment count for current screen width
    segments = getOptimalSegments(logicalWidth);

    // Recreate all segments
    createSegments();
}

window.addEventListener('resize', resizeCanvas);
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

        if (logicalHeight  < 700) {
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

// Animation loop with significant optimizations
function animate(currentTime) {
    requestAnimationFrame(animate);

    // Throttle framerate
    if (currentTime - lastTime < INTERVAL) return;
    lastTime = currentTime;

    // Convert time to seconds
    const time = currentTime / 1000;

    // Clear only what we need
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Update path points - optimize to avoid array lookups in inner loop
    for (const pathKey of pathKeys) {
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

    // Draw all paths - back to front
    for (const pathKey of reversedPathKeys) {
        drawPath(path_points[pathKey], paths[pathKey].fillStyle);
    }
}

// Start animation
requestAnimationFrame(animate);
