# Northern Colorado EMS Protocols Website - Technical Reference Guide

## Tech Stack

- **Frontend**: HTML5, CSS3, Bootstrap 5, TinyMCE 6
- **Backend**: PHP 8.1+, MySQL 8.0+
- **JavaScript Libraries**: jQuery 3.6, jQuery UI 1.13 (drag/drop), Bootstrap JS Bundle
- **Icons**: Tabler Icons (via CDN)
- **Hosting**: SiteGround (shared hosting)

## Project Structure

```
/
├── admin/             # Admin panel files
│   ├── includes/      # Admin-specific includes
│   └── [various].php  # Admin page controllers
├── api/               # Backend API endpoints
│   ├── get_branch.php
│   ├── get_branches.php
│   ├── get_section.php
│   └── upload_image.php
├── assets/            # Frontend assets
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   ├── img/           # Site images
│   ├── icons/         # Protocol category icons
│   └── uploads/       # User uploaded images (TinyMCE)
├── includes/          # Shared PHP files
│   ├── config.php     # Site configuration
│   ├── db.php         # Database functions
│   ├── auth.php       # Authentication system
│   ├── functions.php  # Shared utility functions
│   ├── frontend_header.php 
│   ├── frontend_footer.php
│   └── protocol_content.php
└── [various].php      # Frontend page controllers
```

## Style Guide

### Color Palette
- **Primary**: `#106e9e` (Blue) - Main site color
- **Secondary**: `#0c5578` (Dark Blue) - Header backgrounds
- **Success**: `#28a745` (Green) - "Yes" decisions, treatments
- **Danger**: `#dc3545` (Red) - "No" decisions, warnings
- **Warning**: `#ffc107` (Yellow) - Decision points
- **Info**: `#17a2b8` (Light Blue) - Info buttons/modals
- **Light**: `#f8f9fa` (Off-White) - Light backgrounds
- **Dark**: `#343a40` (Dark Gray) - Footer, text

### Typography
- **Primary Font**: System fonts stack (`-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif`)
- **Headings**: Normal weight, primary color
- **Section Headers**: Font weight 600, size 1.1rem
- **Body Text**: 16px, line-height 1.6

### Components
- **Protocol Sections**: Color-coded by type, with appropriate icons
  - Entry Points: Blue left border, light blue background
  - Treatments: Green left border, light green background
  - Decisions: Yellow left border, light yellow background
  - Notes: Gray left border, light gray background
  - References: Purple left border, light purple background
- **Skill Level Pills**: Color-coded by certification level
- **Info Modals**: Bootstrap modals with custom styling

## Development Patterns

### Database Operations
- Use prepared statements via the wrapper functions in `includes/db.php`
- Key functions: `db_query()`, `db_get_row()`, `db_get_rows()`, `db_insert()`, `db_update()`, `db_delete()`

### Authentication
- Session-based authentication system in `includes/auth.php`
- Functions: `login()`, `logout()`, `is_logged_in()`, `require_login()`, `require_admin()`

### Protocol Editor Components
- Protocol editor uses jQuery UI for drag and drop
- TinyMCE for rich text editing with custom modal button plugin
- Bootstrap modals for UI components with custom forms

### Image Handling
- TinyMCE uploads images to server via `api/upload_image.php`
- Category icons are stored in `assets/icons/`

## Implementation Notes for AI

2. **Protocol Display Styling**
   - Section styling is controlled via CSS classes in `frontend_header.php`
   - The protocol content template in `protocol_content.php` renders all sections

3. **Component Template System**
   - Component templates are stored in the database
   - They can be selected in the protocol editor

4. **AJAX Operations**
   - Protocol editing uses AJAX calls for section editing, branches, etc.
   - All API endpoints return JSON responses

5. **Interactive Elements**
   - Info modals are generated with Bootstrap modal + jQuery
   - Decision trees use yes/no branches with conditional logic

## Future Development Directions

1. **Mobile App Integration**: Consider creating API endpoints for mobile app consumption
2. **Offline Capability**: Implement service workers for offline protocol access
3. **PDF Generation**: Add PDF export functionality for printing protocols
4. **Search Enhancements**: Implement full-text search indexing for better search results
5. **User Analytics**: Add usage tracking to see which protocols are viewed most
6. **Version Control**: Implement protocol versioning to track changes over time
7. **Medication Database**: Create a detailed medication reference section
8. **Interactive Algorithms**: Add flowchart visualization for decision trees

---
