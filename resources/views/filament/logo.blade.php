<svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <marker
            id="arrowhead"
            markerWidth="2.5"
            markerHeight="2.5"
            refX="0"  refY="1.25"
            orient="auto"
            markerUnits="strokeWidth">
            <polygon points="2.5 1.25, 0 0, 0 2.5" fill="#3B82F6" />
        </marker>
    </defs>

    <style>
        /* Styles for the circular arrow path */
        .arrow-path {
            stroke: #3B82F6; /* Tailwind CSS blue-500 */
            stroke-width: 4.5; /* Stroke thickness for the arrow */
            fill: none; /* The arrow path is not filled, only stroked */
            stroke-linecap: round; /* Makes the start of the arc (non-arrowhead end) rounded */
        }

        /* Styles for the task representation bars */
        .task-bar {
            fill: #9CA3AF; /* Tailwind CSS gray-400. A neutral color for tasks. */
        }
    </style>

    <path
        class="arrow-path"
        d="M 51.05 21 A 22 22 0 1 1 21 12.95"
        marker-end="url(#arrowhead)" />

    <rect class="task-bar" x="18" y="24" width="28" height="4" rx="2"/>
    <rect class="task-bar" x="18" y="30" width="28" height="4" rx="2"/>
    <rect class="task-bar" x="18" y="36" width="28" height="4" rx="2"/>
</svg>
