// Inline SVG path data (stroke="currentColor") for the 5 fixed product specs,
// shared across admin form, product detail, and product catalog card.
export const SPEC_ICONS = {
    // Open box / package — represents load capacity (own redraw, not derived
    // from any third-party brand asset; same family as Heroicons ArchiveBox)
    "Kapasitas Angkat": [
        "M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5C21.75 4.254 21.246 3.75 20.625 3.75H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z",
        "M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5",
        "M10 11.25h4",
    ],
    "Tinggi Angkat Maks": ["M12 19.5V4.5m0 0l-6 6m6-6l6 6"],
    Otomatis: [
        "M9 12.75L11.25 15 15 9.75",
        "M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
    ],
    "Kecepatan Jalan": [
        "M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z",
    ],
    "Kapasitas Penarik": [
        "M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244",
    ],
};
