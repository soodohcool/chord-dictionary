// chordDictionary.js
export const chordDictionary = {
  // Major chords
  'A': {
    name: 'A',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 2, finger: '3' },
      { string: 2, fret: 2, finger: '4' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'B': {
    name: 'B',
    fingers: [
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 4, finger: '3' },
      { string: 3, fret: 4, finger: '4' },
      { string: 2, fret: 4, finger: '4' },
      { string: 1, fret: 2, finger: '1' },
    ],
    muted: [6]
  },
  'C': {
    name: 'C',
    fingers: [
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'D': {
    name: 'D',
    fingers: [
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '1' },
      { string: 2, fret: 3, finger: '3' },
      { string: 1, fret: 2, finger: '2' },
    ],
    muted: [6, 5]
  },
  'E': {
    name: 'E',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 2, finger: '3' },
      { string: 3, fret: 1, finger: '1' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },
  'F': {
    name: 'F',
    fingers: [
      { string: 6, fret: 1, finger: '1' },
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 3, finger: '4' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: []
  },
  'G': {
    name: 'G',
    fingers: [
      { string: 6, fret: 3, finger: '2' },
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 3, finger: '3' },
    ],
    muted: []
  },

  // Minor chords
  'Am': {
    name: 'Am',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 2, finger: '3' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'Bm': {
    name: 'Bm',
    fingers: [
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 4, finger: '3' },
      { string: 3, fret: 4, finger: '4' },
      { string: 2, fret: 3, finger: '2' },
      { string: 1, fret: 2, finger: '1' },
    ],
    muted: [6]
  },
  'Cm': {
    name: 'Cm',
    fingers: [
      { string: 5, fret: 3, finger: '1' },
      { string: 4, fret: 5, finger: '3' },
      { string: 3, fret: 5, finger: '4' },
      { string: 2, fret: 4, finger: '2' },
      { string: 1, fret: 3, finger: '1' },
    ],
    muted: [6]
  },
  'Dm': {
    name: 'Dm',
    fingers: [
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 3, finger: '3' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: [6, 5]
  },
  'Em': {
    name: 'Em',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 2, finger: '3' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },
  'Fm': {
    name: 'Fm',
    fingers: [
      { string: 6, fret: 1, finger: '1' },
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 3, finger: '4' },
      { string: 3, fret: 1, finger: '1' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: []
  },
  'Gm': {
    name: 'Gm',
    fingers: [
      { string: 6, fret: 3, finger: '3' },
      { string: 5, fret: 5, finger: '5' },
      { string: 4, fret: 5, finger: '5' },
      { string: 3, fret: 3, finger: '1' },
      { string: 2, fret: 3, finger: '1' },
      { string: 1, fret: 3, finger: '1' },
    ],
    muted: []
  },

  // 7th chords
  'A7': {
    name: 'A7',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 2, finger: '3' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'B7': {
    name: 'B7',
    fingers: [
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 4, finger: '3' },
      { string: 3, fret: 2, finger: '1' },
      { string: 2, fret: 4, finger: '4' },
      { string: 1, fret: 2, finger: '1' },
    ],
    muted: [6]
  },
  'C7': {
    name: 'C7',
    fingers: [
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 3, finger: '4' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'D7': {
    name: 'D7',
    fingers: [
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 2, finger: '3' },
    ],
    muted: [6, 5]
  },
  'E7': {
    name: 'E7',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 1, finger: '1' },
      { string: 2, fret: 3, finger: '4' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },
  'F7': {
    name: 'F7',
    fingers: [
      { string: 6, fret: 1, finger: '1' },
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 1, finger: '1' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: []
  },
  'G7': {
    name: 'G7',
    fingers: [
      { string: 6, fret: 3, finger: '3' },
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: []
  },

  // Major 7th chords
  'Amaj7': {
    name: 'Amaj7',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 1, finger: '1' },
      { string: 2, fret: 2, finger: '3' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'Bmaj7': {
    name: 'Bmaj7',
    fingers: [
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 4, finger: '3' },
      { string: 3, fret: 3, finger: '2' },
      { string: 2, fret: 4, finger: '4' },
      { string: 1, fret: 2, finger: '1' },
    ],
    muted: [6]
  },
  'Cmaj7': {
    name: 'Cmaj7',
    fingers: [
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'Dmaj7': {
    name: 'Dmaj7',
    fingers: [
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 2, finger: '1' },
      { string: 1, fret: 2, finger: '3' },
    ],
    muted: [6, 5]
  },
  'Emaj7': {
    name: 'Emaj7',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 1, finger: '2' },
      { string: 3, fret: 1, finger: '3' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },
  'Fmaj7': {
    name: 'Fmaj7',
    fingers: [
      { string: 6, fret: 1, finger: '1' },
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: []
  },
  'Gmaj7': {
    name: 'Gmaj7',
    fingers: [
      { string: 6, fret: 3, finger: '3' },
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 2, finger: '1' },
    ],
    muted: []
  },

  // Minor 7th chords
  'Am7': {
    name: 'Am7',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'Bm7': {
    name: 'Bm7',
    fingers: [
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 4, finger: '3' },
      { string: 3, fret: 2, finger: '1' },
      { string: 2, fret: 3, finger: '2' },
      { string: 1, fret: 2, finger: '1' },
    ],
    muted: [6]
  },
  'Cm7': {
    name: 'Cm7',
    fingers: [
      { string: 5, fret: 3, finger: '1' },
      { string: 4, fret: 5, finger: '3' },
      { string: 3, fret: 3, finger: '1' },
      { string: 2, fret: 4, finger: '2' },
      { string: 1, fret: 3, finger: '1' },
    ],
    muted: [6]
  },
  'Dm7': {
    name: 'Dm7',
    fingers: [
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: [6, 5]
  },
  'Em7': {
    name: 'Em7',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },
  'Fm7': {
    name: 'Fm7',
    fingers: [
      { string: 6, fret: 1, finger: '1' },
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 1, finger: '1' },
      { string: 3, fret: 1, finger: '1' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: []
  },
  'Gm7': {
    name: 'Gm7',
    fingers: [
      { string: 6, fret: 3, finger: '3' },
      { string: 5, fret: 5, finger: '4' },
      { string: 4, fret: 3, finger: '1' },
      { string: 3, fret: 3, finger: '1' },
      { string: 2, fret: 3, finger: '1' },
      { string: 1, fret: 3, finger: '1' },
    ],
    muted: []
  },

  // Dominant 9th chords
  'A9': {
    name: 'A9',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'B9': {
    name: 'B9',
    fingers: [
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 1, finger: '1' },
      { string: 3, fret: 2, finger: '3' },
      { string: 2, fret: 2, finger: '4' },
    ],
    muted: [6, 1]
  },
  'C9': {
    name: 'C9',
    fingers: [
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 2, finger: '1' },
      { string: 3, fret: 3, finger: '4' },
      { string: 2, fret: 3, finger: '4' },
      { string: 1, fret: 3, finger: '4' },
    ],
    muted: [6]
  },
  'D9': {
    name: 'D9',
    fingers: [
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '1' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6, 5]
  },
  'E9': {
    name: 'E9',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 1, finger: '2' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 2, finger: '3' },
    ],
    muted: []
  },
  'F9': {
    name: 'F9',
    fingers: [
      { string: 6, fret: 1, finger: '1' },
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 1, finger: '1' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 3, finger: '4' },
    ],
    muted: []
  },
  'G9': {
    name: 'G9',
    fingers: [
      { string: 6, fret: 3, finger: '3' },
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: []
  },

  // Major 6th chords
  'A6': {
    name: 'A6',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 2, finger: '1' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 2, finger: '3' },
      { string: 1, fret: 2, finger: '4' },
    ],
    muted: [6]
  },
  'B6': {
    name: 'B6',
    fingers: [
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 4, finger: '3' },
      { string: 3, fret: 4, finger: '4' },
      { string: 2, fret: 4, finger: '4' },
      { string: 1, fret: 4, finger: '4' },
    ],
    muted: [6]
  },
  'C6': {
    name: 'C6',
    fingers: [
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 2, finger: '1' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'D6': {
    name: 'D6',
    fingers: [
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '1' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 2, finger: '3' },
    ],
    muted: [6, 5]
  },
  'E6': {
    name: 'E6',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 2, finger: '3' },
      { string: 3, fret: 1, finger: '1' },
      { string: 2, fret: 2, finger: '4' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },
  'F6': {
    name: 'F6',
    fingers: [
      { string: 6, fret: 1, finger: '1' },
      { string: 5, fret: 3, finger: '4' },
      { string: 4, fret: 3, finger: '3' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 3, finger: '4' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: []
  },
  'G6': {
    name: 'G6',
    fingers: [
      { string: 6, fret: 3, finger: '4' },
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },

  // Minor 6th chords
  'Am6': {
    name: 'Am6',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 2, finger: '3' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 2, finger: '4' },
    ],
    muted: [6]
  },
  'Bm6': {
    name: 'Bm6',
    fingers: [
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 4, finger: '3' },
      { string: 3, fret: 4, finger: '4' },
      { string: 2, fret: 3, finger: '2' },
      { string: 1, fret: 4, finger: '4' },
    ],
    muted: [6]
  },
  'Cm6': {
    name: 'Cm6',
    fingers: [
      { string: 5, fret: 3, finger: '1' },
      { string: 4, fret: 5, finger: '3' },
      { string: 3, fret: 5, finger: '4' },
      { string: 2, fret: 4, finger: '2' },
      { string: 1, fret: 5, finger: '4' },
    ],
    muted: [6]
  },
  'Dm6': {
    name: 'Dm6',
    fingers: [
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: [6, 5]
  },
  'Em6': {
    name: 'Em6',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 2, finger: '3' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 2, finger: '4' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },
  'Fm6': {
    name: 'Fm6',
    fingers: [
      { string: 6, fret: 1, finger: '1' },
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 3, finger: '4' },
      { string: 3, fret: 1, finger: '1' },
      { string: 2, fret: 3, finger: '3' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: []
  },
  'Gm6': {
    name: 'Gm6',
    fingers: [
      { string: 6, fret: 3, finger: '3' },
      { string: 5, fret: 5, finger: '4' },
      { string: 4, fret: 3, finger: '2' },
      { string: 3, fret: 3, finger: '1' },
      { string: 2, fret: 3, finger: '1' },
      { string: 1, fret: 3, finger: '1' },
    ],
    muted: []
  },

  // Suspended chords (sus2, sus4)
  'Asus2': {
    name: 'Asus2',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 2, finger: '1' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'Asus4': {
    name: 'Asus4',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 2, finger: '3' },
      { string: 2, fret: 3, finger: '4' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'Dsus2': {
    name: 'Dsus2',
    fingers: [
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '1' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6, 5]
  },
  'Dsus4': {
    name: 'Dsus4',
    fingers: [
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '1' },
      { string: 2, fret: 3, finger: '3' },
      { string: 1, fret: 3, finger: '4' },
    ],
    muted: [6, 5]
  },
  'Esus2': {
    name: 'Esus2',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 4, finger: '3' },
      { string: 3, fret: 4, finger: '4' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },
  'Esus4': {
    name: 'Esus4',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 2, finger: '3' },
      { string: 3, fret: 2, finger: '4' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },
  'Fsus2': {
    name: 'Fsus2',
    fingers: [
      { string: 6, fret: 1, finger: '1' },
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 3, finger: '4' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: []
  },
  'Fsus4': {
    name: 'Fsus4',
    fingers: [
      { string: 6, fret: 1, finger: '1' },
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 3, finger: '4' },
      { string: 3, fret: 3, finger: '4' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: []
  },
  'Gsus2': {
    name: 'Gsus2',
    fingers: [
      { string: 6, fret: 3, finger: '3' },
      { string: 5, fret: 5, finger: '4' },
      { string: 4, fret: 5, finger: '4' },
      { string: 3, fret: 5, finger: '4' },
      { string: 2, fret: 3, finger: '1' },
      { string: 1, fret: 3, finger: '1' },
    ],
    muted: []
  },
  'Gsus4': {
    name: 'Gsus4',
    fingers: [
      { string: 6, fret: 3, finger: '3' },
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 3, finger: '4' },
    ],
    muted: []
  },

  // Augmented chords
  'Aaug': {
    name: 'Aaug',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 3, finger: '4' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 2, finger: '3' },
      { string: 1, fret: 1, finger: '1' },
    ],
    muted: [6]
  },
  'Caug': {
    name: 'Caug',
    fingers: [
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 1, finger: '1' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'Eaug': {
    name: 'Eaug',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 1, finger: '1' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },
  'Gaug': {
    name: 'Gaug',
    fingers: [
      { string: 6, fret: 3, finger: '3' },
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 1, finger: '1' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 3, finger: '4' },
    ],
    muted: []
  },

  // Diminished chords
  'Adim': {
    name: 'Adim',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 1, finger: '1' },
      { string: 3, fret: 2, finger: '3' },
      { string: 2, fret: 1, finger: '2' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'Bdim': {
    name: 'Bdim',
    fingers: [
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 3, finger: '3' },
      { string: 3, fret: 4, finger: '4' },
      { string: 2, fret: 3, finger: '3' },
      { string: 1, fret: 2, finger: '1' },
    ],
    muted: [6]
  },
  'Ddim': {
    name: 'Ddim',
    fingers: [
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 1, finger: '1' },
      { string: 2, fret: 3, finger: '4' },
      { string: 1, fret: 2, finger: '3' },
    ],
    muted: [6, 5]
  },
  'Edim': {
    name: 'Edim',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 2, finger: '3' },
      { string: 4, fret: 1, finger: '1' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },

  // Dominant 7th flat 5 chords
  'A7b5': {
    name: 'A7b5',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 1, finger: '1' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 2, finger: '3' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'B7b5': {
    name: 'B7b5',
    fingers: [
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 3, finger: '3' },
      { string: 3, fret: 2, finger: '1' },
      { string: 2, fret: 4, finger: '4' },
      { string: 1, fret: 2, finger: '1' },
    ],
    muted: [6]
  },
  'E7b5': {
    name: 'E7b5',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 1, finger: '1' },
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 1, finger: '2' },
      { string: 2, fret: 3, finger: '4' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },

  // Add9 chords
  'Cadd9': {
    name: 'Cadd9',
    fingers: [
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 3, finger: '4' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6]
  },
  'Dadd9': {
    name: 'Dadd9',
    fingers: [
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 3, finger: '3' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: [6, 5]
  },
  'Gadd9': {
    name: 'Gadd9',
    fingers: [
      { string: 6, fret: 3, finger: '3' },
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 3, finger: '4' },
    ],
    muted: []
  },

  // Slash chords (with different bass notes)
  'C/G': {
    name: 'C/G',
    fingers: [
      { string: 6, fret: 3, finger: '3' },
      { string: 5, fret: 3, finger: '4' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },
  'D/F#': {
    name: 'D/F#',
    fingers: [
      { string: 6, fret: 2, finger: '1' },
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 3, finger: '4' },
      { string: 1, fret: 2, finger: '3' },
    ],
    muted: [5]
  },
  'G/B': {
    name: 'G/B',
    fingers: [
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 0, finger: '0' },
      { string: 2, fret: 0, finger: '0' },
      { string: 1, fret: 3, finger: '4' },
    ],
    muted: [6]
  },
  'Am/G': {
    name: 'Am/G',
    fingers: [
      { string: 6, fret: 3, finger: '3' },
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 2, finger: '1' },
      { string: 2, fret: 1, finger: '1' },
      { string: 1, fret: 0, finger: '0' },
    ],
    muted: []
  },

  // Power chords (5 chords)
  'A5': {
    name: 'A5',
    fingers: [
      { string: 5, fret: 0, finger: '0' },
      { string: 4, fret: 2, finger: '2' },
      { string: 3, fret: 2, finger: '3' },
    ],
    muted: [6, 2, 1]
  },
  'B5': {
    name: 'B5',
    fingers: [
      { string: 5, fret: 2, finger: '1' },
      { string: 4, fret: 4, finger: '3' },
      { string: 3, fret: 4, finger: '4' },
    ],
    muted: [6, 2, 1]
  },
  'C5': {
    name: 'C5',
    fingers: [
      { string: 5, fret: 3, finger: '1' },
      { string: 4, fret: 5, finger: '3' },
      { string: 3, fret: 5, finger: '4' },
    ],
    muted: [6, 2, 1]
  },
  'D5': {
    name: 'D5',
    fingers: [
      { string: 4, fret: 0, finger: '0' },
      { string: 3, fret: 2, finger: '2' },
      { string: 2, fret: 3, finger: '3' },
    ],
    muted: [6, 5, 1]
  },
  'E5': {
    name: 'E5',
    fingers: [
      { string: 6, fret: 0, finger: '0' },
      { string: 5, fret: 2, finger: '2' },
      { string: 4, fret: 2, finger: '3' },
    ],
    muted: [3, 2, 1]
  },
  'F5': {
    name: 'F5',
    fingers: [
      { string: 6, fret: 1, finger: '1' },
      { string: 5, fret: 3, finger: '3' },
      { string: 4, fret: 3, finger: '4' },
    ],
    muted: [3, 2, 1]
  },
  'G5': {
    name: 'G5',
    fingers: [
      { string: 6, fret: 3, finger: '3' },
      { string: 5, fret: 5, finger: '4' },
      { string: 4, fret: 5, finger: '4' },
    ],
    muted: [3, 2, 1]
  }
};

/**
 * Get chord data by chord name
 * @param {string} chord - Chord name (e.g., "Am", "C", "G")
 * @returns {Object|null} - Chord data or null if not found
 */
export function getChordData(chord) {
  // Defensive check to handle null/undefined
  if (!chord) return null;

  return chordDictionary[chord] || null;
}

/**
 * Check if a string is muted for a given chord
 * @param {string} chord - Chord name
 * @param {number} stringNum - String number (1-6)
 * @returns {boolean} - True if string is muted
 */
export function isStringMuted(chord, stringNum) {
  const data = getChordData(chord);
  return data && Array.isArray(data.muted) && data.muted.includes(stringNum);
}

/**
 * Check if a string is played open (fret 0)
 * @param {string} chord - Chord name
 * @param {number} stringNum - String number (1-6)
 * @returns {boolean} - True if string is open
 */
export function isOpenString(chord, stringNum) {
  const data = getChordData(chord);
  return data &&
    Array.isArray(data.fingers) &&
    data.fingers.some(f => f && f.string === stringNum && f.fret === 0);
}
