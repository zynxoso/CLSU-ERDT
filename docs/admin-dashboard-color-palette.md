# Admin Dashboard Color Palette Documentation

## Overview
This document provides a comprehensive overview of all colors used in the CLSU ERDT Admin Dashboard (`/resources/views/admin/dashboard.blade.php`). The color scheme follows a professional design system with CLSU's green theme as the primary brand color.

## Primary Brand Colors

### CLSU Green Theme
- **Primary Green**: `#4CAF50`
  - Used for: Primary buttons, icons, active states, success indicators
  - Hover state: `#43A047`

- **Green Variants**:
  - Light green background: `rgba(76, 175, 80, 0.1)` (10% opacity)
  - Gradient start: `rgba(76, 175, 80, 0.05)` (5% opacity)
  - Gradient end: `rgba(76, 175, 80, 0.1)` (10% opacity)
  - Dark green text: `#2E7D32`

## Neutral Colors

### Gray Scale
- **Primary Text**: `#424242` - Main headings, important text
- **Secondary Text**: `#757575` - Labels, secondary information
- **Muted Text**: `#9E9E9E` - Helper text, timestamps, placeholders
- **Light Gray**: `#F5F5F5` - Borders, dividers, hover backgrounds
- **Border Gray**: `#E0E0E0` - Card borders, input borders
- **Background Gray**: `#FAFAFA` - Page background
- **Modal Overlay**: `rgba(117, 117, 117, 0.5)` - Background overlay with 50% opacity

### White
- **Pure White**: `white` / `#FFFFFF` - Card backgrounds, modal backgrounds
- **Transparent**: `transparent` - Reset backgrounds

## Status Colors

### Success States
- **Success Green**: `#4CAF50` - Approved status, success indicators
- **Success Background**: `rgba(76, 175, 80, 0.1)` - Success badge backgrounds

### Warning States
- **Warning Yellow**: `#FFCA28` - Pending status, warning indicators
- **Warning Background**: `rgba(255, 202, 40, 0.1)` - Warning badge backgrounds
- **Warning Text**: `#975A16` - Warning badge text

### Error States
- **Error Red**: `#D32F2F` - Rejected status, error indicators, notification badges
- **Error Background**: `rgba(211, 47, 47, 0.1)` - Error badge backgrounds

### Info States
- **Info Blue**: `#4A90E2` - Submitted status, info indicators
- **Info Background**: `rgba(74, 144, 226, 0.1)` - Info badge backgrounds

## Notification Type Colors

### Fund Request
- **Icon Color**: `#4CAF50` (CLSU Green)
- **Background**: `rgba(76, 175, 80, 0.1)`
- **Badge Text**: `#2E7D32`
- **Unread Border**: `#4CAF50`
- **Unread Background**: `rgba(76, 175, 80, 0.05)`

### Document
- **Icon Color**: `#9C27B0` (Purple)
- **Background**: `rgba(156, 39, 176, 0.1)`
- **Badge Text**: `#7B1FA2`

### Manuscript
- **Icon Color**: `#3F51B5` (Indigo)
- **Background**: `rgba(63, 81, 181, 0.1)`
- **Badge Text**: `#303F9F`

### General/Default
- **Icon Color**: `#757575` (Gray)
- **Background**: `rgba(117, 117, 117, 0.1)`
- **Badge Text**: `#424242`

## Action Colors

### CRUD Operations
- **Create**: `#4CAF50` (Green) - Create actions, new items
- **Update**: `#FFCA28` (Yellow) - Update actions, modifications
- **Delete**: `#D32F2F` (Red) - Delete actions, removals
- **Login**: `#4A90E2` (Blue) - Login actions
- **Logout**: `#9C27B0` (Purple) - Logout actions

## Interactive States

### Hover Effects
- **Button Hover**: `#43A047` (Darker green)
- **Link Hover**: `opacity: 0.8`
- **Notification Button Hover**: `#424242` with `#F5F5F5` background
- **Secondary Button Hover**: `#F5F5F5` background

### Focus States
- **Focus Outline**: Uses browser default or `focus:outline-none` with custom styling

## Special Elements

### Gradients
- **Notification Header**: `linear-gradient(to right, rgba(76, 175, 80, 0.05), rgba(76, 175, 80, 0.1))`

### Shadows
- **Card Shadow**: `shadow-sm`, `shadow`, `shadow-xl` (Tailwind CSS classes)
- **Modal Shadow**: `shadow-xl`

### Borders
- **Card Borders**: `1px solid #E0E0E0`
- **Input Borders**: `1px solid #E0E0E0`
- **Notification Borders**: `border-color: #F5F5F5`
- **Unread Notification Left Border**: `4px solid #4CAF50`

## Typography Colors

### Text Hierarchy
1. **Primary Headings**: `#424242` - Main titles, important information
2. **Secondary Text**: `#757575` - Labels, descriptions
3. **Muted Text**: `#9E9E9E` - Helper text, timestamps
4. **White Text**: `white` - Text on colored backgrounds

## Background Colors

### Page Backgrounds
- **Main Background**: `#FAFAFA`
- **Card Background**: `white`
- **Modal Background**: `white`

### Component Backgrounds
- **Icon Backgrounds**: Various `rgba()` colors with 10% opacity
- **Badge Backgrounds**: Various `rgba()` colors with 10% opacity
- **Empty State Backgrounds**: `#F5F5F5`

## Usage Guidelines

### Primary Actions
- Use `#4CAF50` for primary buttons and main call-to-action elements
- Use hover state `#43A047` for interactive feedback

### Status Indicators
- Green (`#4CAF50`) for success/approved states
- Yellow (`#FFCA28`) for pending/warning states
- Red (`#D32F2F`) for error/rejected states
- Blue (`#4A90E2`) for info/submitted states

### Text Contrast
- Ensure sufficient contrast ratios for accessibility
- Use `#424242` for primary text on white backgrounds
- Use `white` text on colored backgrounds when contrast is sufficient

### Consistency
- Maintain consistent color usage across similar components
- Use the same color for the same type of status or action throughout the interface
- Follow the established opacity patterns for background colors (typically 10% for subtle backgrounds)

## Color Accessibility

All colors used in this palette should meet WCAG 2.1 AA standards for color contrast. The chosen colors provide:
- Good contrast between text and background colors
- Clear distinction between different states and types
- Consistent visual hierarchy through color usage

---

*This documentation is based on the analysis of `/resources/views/admin/dashboard.blade.php` and reflects the current color implementation in the CLSU ERDT Admin Dashboard.*