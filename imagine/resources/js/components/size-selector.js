export default function sizeSelectorData(initialCategory = '', initialSize = '') {
    return {
        activeTab: initialCategory,
        selectedSize: initialSize,
        utils: {
            getScaledDimensions(width, height) {
            // Base scale (pixels per inch) - this determines how large the previews will be
            const PIXELS_PER_INCH = 4;
            
            // Calculate initial dimensions
            let scaledWidth = width * PIXELS_PER_INCH;
            let scaledHeight = height * PIXELS_PER_INCH;
            
            // Maximum dimensions we want to allow
            const MAX_WIDTH = 200;
            const MAX_HEIGHT = 160;
            
            // If the scaled dimensions are too large, scale them down proportionally
            if (scaledWidth > MAX_WIDTH || scaledHeight > MAX_HEIGHT) {
                const scaleRatio = Math.min(MAX_WIDTH / scaledWidth, MAX_HEIGHT / scaledHeight);
                scaledWidth *= scaleRatio;
                scaledHeight *= scaleRatio;
            }
            
            // Minimum dimensions to ensure small prints are still visible
            const MIN_WIDTH = 30;
            const MIN_HEIGHT = 30;
            
            // If the scaled dimensions are too small, scale them up proportionally
            if (scaledWidth < MIN_WIDTH || scaledHeight < MIN_HEIGHT) {
                const scaleRatio = Math.max(MIN_WIDTH / scaledWidth, MIN_HEIGHT / scaledHeight);
                scaledWidth *= scaleRatio;
                scaledHeight *= scaleRatio;
            }
            
            return {
                width: Math.round(scaledWidth),
                height: Math.round(scaledHeight)
            };
        },
            getCategoryIcon(category) {
            const icons = {
                'Mini Prints': '<path stroke-linecap="round" stroke-linejoin="round" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />',
                'Photo Prints': '<path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />',
                'Wall Art': '<path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v18H3V3zm16 16V5H5v14h14zm-5.5-5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />',
                'Gallery Prints': '<path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h14a2 2 0 012 2v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />'
            };
            return icons[category] || icons['Wall Art'];
        },
            getSizeRange(category) {
                const ranges = {
                    'Mini Prints': '2.5" × 3.5" - 4" × 6"',
                    'Photo Prints': '5" × 7" - 8" × 10"',
                    'Wall Art': '11" × 14" - 18" × 24"',
                    'Gallery Prints': '20" × 30" - 40" × 60"'
                };
                return ranges[category] || '';
            }
        }
    };
}
