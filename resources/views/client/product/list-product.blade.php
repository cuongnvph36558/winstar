@extends('layouts.client')

@section('title', 'Sản phẩm')

@section('styles')
  <style>
    /* CSS cho action buttons */
    .action-buttons {
      display: flex;
      gap: 6px;
      align-items: center;
      justify-content: space-between;
      flex-wrap: nowrap;
      margin-top: 10px;
      width: 100%;
    }
    
    .action-buttons .btn {
      margin: 0;
      font-size: 11px;
      padding: 6px 8px;
      border-radius: 6px;
      transition: all 0.3s ease;
      font-weight: 500;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      border: 1px solid transparent;
      flex: 1;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    
    .action-buttons .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
      text-decoration: none;
    }
    
    .action-buttons .btn-success {
      background-color: #28a745;
      border-color: #28a745;
      color: white;
    }
    
    .action-buttons .btn-success:hover {
      background-color: #218838;
      border-color: #1e7e34;
      color: white;
    }
    
    .action-buttons .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
      color: white;
    }
    
    .action-buttons .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
      color: white;
    }
    
    .action-buttons .btn-outline-danger {
      color: #dc3545;
      border-color: #dc3545;
      background-color: transparent;
    }
    
    .action-buttons .btn-outline-danger:hover {
      color: white;
      background-color: #dc3545;
      border-color: #dc3545;
    }
    
    .action-buttons .btn-danger {
      background-color: #dc3545;
      border-color: #dc3545;
      color: white;
    }
    
    .action-buttons .btn-danger:hover {
      background-color: #c82333;
      border-color: #bd2130;
      color: white;
    }
    
    .action-buttons .btn i {
      margin-right: 4px;
      font-size: 11px;
    }
    
    /* Nút yêu thích chỉ có icon - nhỏ gọn */
    .action-buttons .btn-outline-danger:last-child,
    .action-buttons .btn-danger:last-child {
      padding: 6px 4px;
      width: 32px;
      height: 32px;
      flex: 0 0 32px;
    }
    
    .action-buttons .btn-outline-danger:last-child i,
    .action-buttons .btn-danger:last-child i {
      margin-right: 0;
      font-size: 12px;
    }
    
    /* Responsive cho mobile */
    @media (max-width: 768px) {
      .action-buttons {
        gap: 4px;
      }
      
      .action-buttons .btn {
        font-size: 10px;
        padding: 5px 6px;
        height: 28px;
      }
      
      .action-buttons .btn-outline-danger:last-child,
      .action-buttons .btn-danger:last-child {
        padding: 5px 3px;
        width: 28px;
        height: 28px;
        flex: 0 0 28px;
      }
      
      .action-buttons .btn-outline-danger:last-child i,
      .action-buttons .btn-danger:last-child i {
        font-size: 11px;
      }
    }
    
    /* Responsive cho màn hình rất nhỏ */
    @media (max-width: 480px) {
      .action-buttons .btn {
        font-size: 9px;
        padding: 4px 4px;
        height: 26px;
      }
      
      .action-buttons .btn-outline-danger:last-child,
      .action-buttons .btn-danger:last-child {
        width: 26px;
        height: 26px;
        flex: 0 0 26px;
      }
    }
.product-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 40vh;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-align: center;
}

.product-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.product-hero p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 2rem;
}

.product-stats {
    display: flex;
    justify-content: center;
    gap: 2rem;
    flex-wrap: wrap;
    }
    
    .stat-item {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 15px;
    padding: 1.5rem 2rem;
    text-align: center;
    min-width: 120px;
    transition: all 0.3s ease;
    }
    
    .stat-item:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-5px);
    }
    
    .stat-number {
    display: block;
    color: #ffd700;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    }
    
    .stat-label {
    display: block;
    color: white;
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.product-content {
    padding: 40px 0;
    background: #f8f9fa;
}

.search-section {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 30px;
    margin-bottom: 40px;
    border: 1px solid #e9ecef;
    }
    
    .search-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 15px;
    }
    
    .search-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    }
    
    .search-summary {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    }
    
    .product-count {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
    }
    
    .clear-all-btn {
    background: #e74c3c;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    }
    
    .clear-all-btn:hover {
    background: #c0392b;
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.search-form {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 15px;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 10px 15px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
    width: 100%;
    color: #333;
    font-weight: 500;
    line-height: 1.4;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background: white;
    outline: none;
}

/* Fix dropdown styling */
select.form-control {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 16px;
    padding-right: 40px;
    color: #333 !important;
    font-weight: 500;
}

select.form-control option {
    background: white;
    color: #333;
    padding: 8px;
    font-weight: 500;
}

select.form-control:focus option:hover {
    background: #667eea;
    color: white;
}

/* Ensure text is visible in all form controls */
.form-control::placeholder {
    color: #6c757d;
    opacity: 1;
    font-weight: 400;
}

.form-control:focus::placeholder {
    color: #667eea;
    opacity: 0.7;
}

/* Price Range Slider Styles */
.price-range-section {
    margin: 25px 0;
    padding: 0;
}

.price-range-container {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 25px;
    margin: 0;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.price-range-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.price-range-label {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.95rem;
    margin: 0;
}

.btn-reset-price {
    background: #6c757d;
    border: none;
    color: white;
    padding: 6px 12px;
    border-radius: 15px;
    font-weight: 500;
    font-size: 0.8rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
}

.btn-reset-price:hover {
    background: #5a6268;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.price-range-label span {
    color: #667eea;
    font-weight: 700;
}

.price-slider-wrapper {
    position: relative;
    height: 30px;
    margin: 20px 0;
    padding: 0 10px;
}

/* Active range display */
.price-slider-wrapper::after {
    content: '';
    position: absolute;
    top: 17px;
    left: 10px;
    right: 10px;
    height: 6px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 3px;
    z-index: 0;
    opacity: 0.3;
}

.price-slider {
    position: absolute;
    width: 100%;
    height: 6px;
    background: transparent;
    border-radius: 3px;
    outline: none;
    pointer-events: none;
    -webkit-appearance: none;
    appearance: none;
    top: 17px;
}

/* Background track for sliders */
.price-slider-wrapper::before {
    content: '';
    position: absolute;
    top: 17px;
    left: 0;
    right: 0;
    height: 6px;
    background: #e9ecef;
    border-radius: 3px;
    z-index: 0;
}

.price-slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    cursor: pointer;
    pointer-events: auto;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
    margin-top: -7px;
}

.price-slider::-webkit-slider-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.price-slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}

.price-slider::-moz-range-thumb:hover {
    transform: scale(1.1);
}

#min-price-slider {
    z-index: 1;
}

#max-price-slider {
    z-index: 1;
}

/* Ensure thumbs are properly positioned */
.price-slider::-webkit-slider-thumb {
    z-index: 10;
}

.price-slider::-moz-range-thumb {
    z-index: 10;
}

.price-inputs {
    display: none;
}

/* Filter Dropdowns Styles */
.filter-dropdowns {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 12px;
    margin: 15px 0;
}

.filter-dropdown {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 10px 15px;
    font-size: 0.9rem;
    color: #333;
    font-weight: 500;
    transition: all 0.3s ease;
}

.filter-dropdown:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background: white;
}

/* Responsive Design */
@media (min-width: 1400px) {
    .products-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
    }
    
    .product-image-container {
        height: 180px;
    }
}

@media (max-width: 768px) {
    .search-form {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .filter-dropdowns {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    
    .price-range-container {
        padding: 20px;
    }
    
    .price-slider-wrapper {
        height: 25px;
    }
    
    .price-slider {
        top: 12px;
    }
    
    .price-slider-wrapper::before {
        top: 12px;
    }
    
    .price-slider-wrapper::after {
        top: 12px;
    }
    
    .price-range-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .btn-reset-price {
        align-self: flex-end;
    }
}

.search-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
}

.btn-primary-search {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 10px 25px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.btn-primary-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-reset {
    background: #6c757d;
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    text-decoration: none;
}

.btn-reset:hover {
    background: #5a6268;
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.active-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    margin-top: 20px;
}

.filter-label {
    font-weight: 600;
    color: #495057;
    margin-right: 10px;
}

.filter-tag {
    background: #667eea;
    color: white;
    padding: 5px 12px;
    border-radius: 15px;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.remove-filter {
    color: white;
    text-decoration: none;
    font-weight: bold;
    margin-left: 5px;
}

.remove-filter:hover {
    color: #ffd700;
    text-decoration: none;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
    margin-bottom: 40px;
    min-height: 400px; /* Ensure minimum height */
}

/* Ensure product cards are visible */
.product-card {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

.product-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.12);
}

.product-image-container {
    position: relative;
    height: 200px;
    overflow: hidden;
    background: #f8f9fa;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
    max-width: 100%;
    max-height: 100%;
    display: block;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.product-image-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    background: #f8f9fa;
    color: #6c757d;
    flex-direction: column;
    gap: 10px;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.product-image-placeholder i {
    font-size: 3rem;
    opacity: 0.5;
}

.product-image-placeholder span {
    font-size: 0.9rem;
    text-align: center;
    padding: 0 10px;
}

.product-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 2;
}

.badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-info {
    background: #17a2b8;
    color: white;
}

.product-content {
    padding: 15px;
}

.product-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 8px;
    line-height: 1.3;
    color: #2c3e50;
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.product-title a {
    color: #2c3e50;
    text-decoration: none;
    transition: color 0.3s ease;
    display: block;
    width: 100%;
}

.product-title a:hover {
    color: #667eea;
}

.product-price {
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.old-price {
    text-decoration: line-through;
    color: #6c757d;
    font-size: 0.9rem;
    font-weight: 500;
}

.new-price, .price {
    color: #e74c3c;
    font-weight: 700;
    font-size: 1.2rem;
    display: inline-block;
}

.product-stats {
    margin-bottom: 10px;
}

.stock-status {
    margin-bottom: 5px;
}

.stock-status small {
    font-size: 0.9rem;
}

.text-success {
    color: #28a745;
}

.text-danger {
    color: #dc3545;
}

.rating-stats {
    text-align: center;
    margin: 5px 0;
    padding: 8px;
    background: #fff3cd;
    border-radius: 8px;
    font-size: 0.9rem;
    color: #856404;
    font-weight: 500;
    line-height: 1.2;
    border: 1px solid #ffeaa7;
}

.rating-stats span {
    font-weight: 600;
    color: #856404;
}

.rating-stats .fa-star,
.rating-stats .fa-star-half-o {
    color: #ffc107 !important;
    margin-right: 1px;
}

.rating-stats .fa-star-o {
    color: #6c757d !important;
    margin-right: 1px;
}

.rating-number {
    margin-left: 5px;
    font-weight: 700;
    color: #856404;
}

.text-warning {
    color: #ffc107 !important;
}

.text-muted {
    color: #6c757d !important;
}

.favorite-stats {
    text-align: center;
    margin: 5px 0;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 8px;
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
    line-height: 1.2;
}

.favorite-stats span {
    font-weight: 600;
    color: #495057;
}

.product-actions {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.btn-xs {
    padding: 4px 8px;
    font-size: 0.75rem;
}

.btn-outline-danger {
    background: transparent;
    color: #dc3545;
    border: 1px solid #dc3545;
}

.btn-outline-danger:hover {
    background: #dc3545;
    color: white;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #c0392b;
}

.btn-info {
    background: #17a2b8;
    color: white;
}

.btn-info:hover {
    background: #138496;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-success:hover {
    background: #218838;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
}

.empty-products {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-title {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #495057;
}

.empty-description {
    font-size: 1.1rem;
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.btn-explore {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    padding: 12px 30px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-explore:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    color: white;
    text-decoration: none;
}

.pagination-wrapper {
    text-align: center;
    margin-top: 40px;
}

.pagination {
    display: inline-flex;
    gap: 8px;
    align-items: center;
}

.pagination .page-link {
    border: none;
    background: #f8f9fa;
    color: #667eea;
    padding: 10px 15px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 600;
}

.pagination .page-link:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

.pagination .active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

@media (max-width: 768px) {
    .product-hero {
        min-height: 30vh;
    }
    
    .product-hero h1 {
        font-size: 2.2rem;
    }
    
    .product-hero p {
        font-size: 1rem;
    }
    
    .product-stats {
        gap: 1rem;
    }
    
    .stat-item {
        min-width: 100px;
        padding: 1rem 1.5rem;
    }
    
    .search-header {
        flex-direction: column;
        align-items: stretch;
    }
    
    .search-form {
        grid-template-columns: 1fr;
    }
    
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    
    .product-image-container {
        height: 160px;
    }
    
    .product-image-placeholder i {
        font-size: 2rem;
    }
    
    .product-image-placeholder span {
        font-size: 0.8rem;
    }
}

@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .product-image-container {
        height: 140px;
    }
    
    .product-content {
        padding: 15px;
    }
    
    .product-title {
        font-size: 1.1rem;
    }
    
    .product-actions {
        flex-direction: column;
        gap: 5px;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="product-hero">
    <div class="container">
        <h1>Cửa hàng sản phẩm</h1>
        <p>Khám phá bộ sưu tập sản phẩm chất lượng cao với nhiều lựa chọn đa dạng</p>
        <div class="product-stats">
              <div class="stat-item">
                <span class="stat-number">{{ $products->total() }}</span>
                <span class="stat-label">Sản phẩm</span>
              </div>
              <div class="stat-item">
                <span class="stat-number">{{ $categories->count() }}</span>
                <span class="stat-label">Danh mục</span>
              </div>
              <div class="stat-item">
                <span class="stat-number">100%</span>
                <span class="stat-label">Chất lượng</span>
        </div>
      </div>
    </div>
  </section>
  
<!-- Search Section -->
<section class="product-content">
    <div class="container">
        <div class="search-section">
            <form method="GET" action="{{ route('client.product') }}">
          <div class="search-header">
                    <h3 class="search-title">
                        <i class="fa fa-filter"></i> Tìm kiếm & Lọc sản phẩm
                    </h3>
            <div class="search-summary">
              <div class="product-count">
                            <i class="fa fa-box"></i>
                            <span>{{ $products->total() }} sản phẩm</span>
              </div>
              @if(request()->hasAny(['name', 'category_id', 'min_price', 'max_price']))
                <a href="{{ route('client.product') }}" class="clear-all-btn">
                  <i class="fa fa-times"></i>
                  <span>Xóa tất cả</span>
                </a>
              @endif
            </div>
          </div>

                <div class="search-form">
                    <input type="text" name="name" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('name') }}">
                    <select name="category_id" class="form-control">
                      <option value="">Tất cả danh mục</option>
                      @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                          {{ $category->name }}
                        </option>
                      @endforeach
                    </select>
                    <select name="sort_by" class="form-control">
                      <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                      <option value="price_low_high" {{ request('sort_by') == 'price_low_high' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                      <option value="price_high_low" {{ request('sort_by') == 'price_high_low' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                      <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Tên: A-Z</option>
                      <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Tên: Z-A</option>
                    </select>
              </div>
              
              <!-- Additional Filter Dropdowns -->
              <div class="filter-dropdowns">
                  <select name="filter_favorites" class="form-control filter-dropdown">
                      <option value="">Lọc theo yêu thích</option>
                      <option value="most_favorited" {{ request('filter_favorites') == 'most_favorited' ? 'selected' : '' }}>Nhiều yêu thích nhất</option>
                      <option value="least_favorited" {{ request('filter_favorites') == 'least_favorited' ? 'selected' : '' }}>Ít yêu thích nhất</option>
                  </select>
                  
                  <select name="filter_rating" class="form-control filter-dropdown">
                      <option value="">Lọc theo đánh giá</option>
                      <option value="5_stars" {{ request('filter_rating') == '5_stars' ? 'selected' : '' }}>5 sao</option>
                      <option value="4_stars" {{ request('filter_rating') == '4_stars' ? 'selected' : '' }}>4 sao trở lên</option>
                      <option value="3_stars" {{ request('filter_rating') == '3_stars' ? 'selected' : '' }}>3 sao trở lên</option>
                      <option value="2_stars" {{ request('filter_rating') == '2_stars' ? 'selected' : '' }}>2 sao trở lên</option>
                      <option value="1_star" {{ request('filter_rating') == '1_star' ? 'selected' : '' }}>1 sao trở lên</option>
                  </select>
                  
                  <select name="filter_views" class="form-control filter-dropdown">
                      <option value="">Lọc theo lượt xem</option>
                      <option value="most_viewed" {{ request('filter_views') == 'most_viewed' ? 'selected' : '' }}>Nhiều lượt xem nhất</option>
                      <option value="least_viewed" {{ request('filter_views') == 'least_viewed' ? 'selected' : '' }}>Ít lượt xem nhất</option>
                  </select>
                  
                  <select name="filter_buyers" class="form-control filter-dropdown">
                      <option value="">Lọc theo người mua</option>
                      <option value="most_buyers" {{ request('filter_buyers') == 'most_buyers' ? 'selected' : '' }}>Nhiều người mua nhất</option>
                      <option value="least_buyers" {{ request('filter_buyers') == 'least_buyers' ? 'selected' : '' }}>Ít người mua nhất</option>
                  </select>
                  
                  <select name="filter_storage" class="form-control filter-dropdown">
                      <option value="">Lọc theo dung lượng</option>
                      <option value="32gb" {{ request('filter_storage') == '32gb' ? 'selected' : '' }}>32GB</option>
                      <option value="64gb" {{ request('filter_storage') == '64gb' ? 'selected' : '' }}>64GB</option>
                      <option value="128gb" {{ request('filter_storage') == '128gb' ? 'selected' : '' }}>128GB</option>
                      <option value="256gb" {{ request('filter_storage') == '256gb' ? 'selected' : '' }}>256GB</option>
                      <option value="512gb" {{ request('filter_storage') == '512gb' ? 'selected' : '' }}>512GB</option>
                      <option value="1tb" {{ request('filter_storage') == '1tb' ? 'selected' : '' }}>1TB</option>
                  </select>
              </div>
              
              <!-- Price Range Slider - Separate Section -->
              <div class="price-range-section">
                  <div class="price-range-container">
                      <div class="price-range-header">
                          <label class="price-range-label">Khoảng giá: <span id="price-display">{{ number_format(request('min_price', 0)) }}đ - {{ number_format(request('max_price', 50000000)) }}đ</span></label>
                          <button type="button" id="reset-price-range" class="btn-reset-price">
                              <i class="fa fa-refresh"></i>
                              Reset giá
                          </button>
                      </div>
                      <div class="price-slider-wrapper">
                          <input type="range" id="min-price-slider" class="price-slider" min="0" max="50000000" value="{{ request('min_price', 0) }}" step="1000000">
                          <input type="range" id="max-price-slider" class="price-slider" min="0" max="50000000" value="{{ request('max_price', 50000000) }}" step="1000000">
                      </div>
                      <div class="price-inputs">
                          <input type="hidden" name="min_price" id="min-price-input" value="{{ request('min_price', 0) }}">
                          <input type="hidden" name="max_price" id="max-price-input" value="{{ request('max_price', 50000000) }}">
                      </div>
                  </div>
              </div>
              
                <div class="search-actions">
                    <button type="submit" class="btn-primary-search">
                        <i class="fa fa-search"></i>
                        <span>Tìm kiếm</span>
                    </button>
                    <a href="{{ route('client.product') }}" class="btn-reset">
                        <i class="fa fa-refresh"></i>
                        <span>Reset</span>
                    </a>
                </div>

          @if(request()->hasAny(['name', 'category_id', 'min_price', 'max_price', 'sort_by', 'filter_favorites', 'filter_rating', 'filter_views', 'filter_buyers', 'filter_storage']))
              <div class="active-filters">
                <span class="filter-label">Bộ lọc đang áp dụng:</span>
                
                @if(request('name'))
                  <span class="filter-tag">
                    <i class="fa fa-search"></i>
                    "{{ request('name') }}"
                    <a href="{{ request()->fullUrlWithQuery(['name' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
                
                @if(request('category_id'))
                  @php $selectedCategory = $categories->firstWhere('id', request('category_id')) @endphp
                  <span class="filter-tag">
                    <i class="fa fa-tag"></i>
                    {{ $selectedCategory->name ?? '' }}
                    <a href="{{ request()->fullUrlWithQuery(['category_id' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
                
                @if(request('min_price') || request('max_price'))
                  <span class="filter-tag">
                    <i class="fa fa-money-bill-alt"></i>
                    {{ number_format(request('min_price') ?: 0) }}đ - {{ number_format(request('max_price') ?: $maxPrice) }}đ
                    <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
                
                @if(request('filter_favorites'))
                  <span class="filter-tag">
                    <i class="fa fa-heart"></i>
                    {{ request('filter_favorites') == 'most_favorited' ? 'Nhiều yêu thích' : 'Ít yêu thích' }}
                    <a href="{{ request()->fullUrlWithQuery(['filter_favorites' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
                
                @if(request('filter_rating'))
                  <span class="filter-tag">
                    <i class="fa fa-star"></i>
                    {{ request('filter_rating') }} sao trở lên
                    <a href="{{ request()->fullUrlWithQuery(['filter_rating' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
                
                @if(request('filter_views'))
                  <span class="filter-tag">
                    <i class="fa fa-eye"></i>
                    {{ request('filter_views') == 'most_viewed' ? 'Nhiều lượt xem' : 'Ít lượt xem' }}
                    <a href="{{ request()->fullUrlWithQuery(['filter_views' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
                
                @if(request('filter_buyers'))
                  <span class="filter-tag">
                    <i class="fa fa-users"></i>
                    {{ request('filter_buyers') == 'most_buyers' ? 'Nhiều người mua' : 'Ít người mua' }}
                    <a href="{{ request()->fullUrlWithQuery(['filter_buyers' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
                
                @if(request('filter_storage'))
                  <span class="filter-tag">
                    <i class="fa fa-hdd-o"></i>
                    {{ strtoupper(request('filter_storage')) }}
                    <a href="{{ request()->fullUrlWithQuery(['filter_storage' => null]) }}" class="remove-filter">×</a>
                  </span>
                @endif
            </div>
          @endif
        </form>
      </div>
  
      <!-- Products Grid -->
        <div class="products-grid">
            @forelse ($products as $product)
            @php
              $variant = $product->variants->first();
                    $hasStock = false;
                    $totalStock = 0;
                    
                    if($product->variants && $product->variants->count() > 0) {
                        $totalStock = $product->variants->sum('stock_quantity');
                        $hasStock = $totalStock > 0;
                    } else {
                        $totalStock = $product->stock_quantity ?? 0;
                        $hasStock = $totalStock > 0;
                    }
            @endphp
                
                <div class="product-card">
                    <div class="product-image-container">
                  <a href="{{ route('client.single-product', $product->id) }}">
                            @if($product->image && !empty($product->image))
                      <img src="{{ asset('storage/' . $product->image) }}" 
                           alt="{{ $product->name }}" 
                           class="product-image"
                           loading="lazy"
                                     onload="console.log('Image loaded:', '{{ $product->image }}')"
                                     onerror="console.log('Image failed:', '{{ $product->image }}'); this.style.display='none'; this.nextElementSibling.style.display='flex';">
                      <div class="product-image-placeholder" style="display: none;">
                        <i class="fa fa-image"></i>
                        <span>{{ $product->name }}</span>
                      </div>
                    @else
                      <div class="product-image-placeholder">
                        <i class="fa fa-image"></i>
                        <span>{{ $product->name }}</span>
                      </div>
                    @endif
                  </a>
                  
                  @if($product->variants && $product->variants->count() > 1)
                    <div class="product-badge">
                      <span class="badge badge-info">{{ $product->variants->count() }} phiên bản</span>
                    </div>
                  @endif
                  </div>
                    
                    <div class="product-content">
                        <h4 class="product-title">
                    <a href="{{ route('client.single-product', $product->id) }}">{{ $product->name }}</a>
                  </h4>
                        
                        <div class="product-price">
                    @if($product->variants && $product->variants->count() > 0)
                      @if($variant->price && $variant->promotion_price && $variant->promotion_price < $variant->price)
                        <span class="old-price">{{ number_format($variant->price, 0, ',', '.') }}đ</span>
                        <span class="new-price">{{ number_format($variant->promotion_price, 0, ',', '.') }}đ</span>
                      @elseif($variant->price)
                        <span class="price">{{ number_format($variant->price, 0, ',', '.') }}đ</span>
                      @else
                        <span class="price">Liên hệ</span>
                      @endif
                    @else
                      @if($product->price && $product->promotion_price && $product->promotion_price < $product->price)
                                    <span class="old-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                    <span class="new-price">{{ number_format($product->promotion_price, 0, ',', '.') }}đ</span>
                      @elseif($product->price)
                                    <span class="price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                      @else
                        <span class="price">Liên hệ</span>
                      @endif
                    @endif
                  </div>
                        
                        <div class="product-stats">
                    <div class="stock-status">
                      <small>
                        @if($hasStock)
                          <i class="fa fa-check text-success"></i> Còn hàng 
                          @if($product->variants && $product->variants->count() > 1)
                            ({{ $totalStock }} - {{ $product->variants->count() }} phiên bản)
                          @else
                            ({{ $totalStock }})
                          @endif
                        @else
                          <i class="fa fa-times text-danger"></i> Hết hàng
                        @endif
                      </small>
                    </div>
                    
                    <div class="rating-stats">
                      <small>
                        @php
                          $avgRating = $product->reviews_avg_rating ?? 0;
                          $reviewsCount = $product->reviews_count ?? 0;
                          // Debug info
                          // echo "Product: " . $product->id . ", Rating: " . $avgRating . ", Count: " . $reviewsCount;
                        @endphp
                        @if($avgRating > 0)
                          @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($avgRating))
                              <i class="fa fa-star text-warning"></i>
                            @elseif($i == ceil($avgRating) && $avgRating - floor($avgRating) > 0)
                              <i class="fa fa-star-half-o text-warning"></i>
                            @else
                              <i class="fa fa-star-o text-muted"></i>
                            @endif
                          @endfor
                          <span class="rating-number">{{ number_format($avgRating, 1) }}</span>
                        @else
                          <i class="fa fa-star-o text-muted"></i>
                          <i class="fa fa-star-o text-muted"></i>
                          <i class="fa fa-star-o text-muted"></i>
                          <i class="fa fa-star-o text-muted"></i>
                          <i class="fa fa-star-o text-muted"></i>
                          <span class="rating-number">0.0</span>
                        @endif
                        ({{ $reviewsCount }} đánh giá)
                        | 
                        👥 <span>{{ $product->buyers_count ?? 0 }}</span> người mua
                      </small>
                    </div>
                    
                            <div class="favorite-stats">
                        ❤️ <span class="product-{{ $product->id }}-favorites favorite-count-display" 
                              data-product-id="{{ $product->id }}"
                              data-count="{{ $product->favorites_count ?? 0 }}">{{ $product->favorites_count ?? 0 }}</span> yêu thích 
                        | 
                                👁️ <span>{{ $product->view ?? 0 }}</span> lượt xem
                            </div>
                    </div>
                    
                        <div class="product-actions">
                          <div class="action-buttons">
                            <!-- Nút Mua ngay -->
                            @if($hasStock)
                              <button type="button" class="btn btn-xs btn-success btn-buy-now" 
                                      data-product-id="{{ $product->id }}"
                                      data-product-name="{{ $product->name }}"
                                      title="Mua ngay">
                                <i class="fa fa-bolt"></i> Mua ngay
                              </button>
                            @else
                              <span class="btn btn-xs btn-secondary disabled">
                                <i class="fa fa-times"></i> Hết hàng
                              </span>
                            @endif
                            
                            <!-- Nút Thêm giỏ hàng -->
                            @if($hasStock)
                              <button type="button" class="btn btn-xs btn-primary btn-select-variant" 
                                      data-product-id="{{ $product->id }}"
                                      data-product-name="{{ $product->name }}"
                                      title="Chọn phiên bản">
                                <i class="fa fa-plus"></i> Thêm giỏ hàng
                              </button>
                              

                            @endif
                            
                            <!-- Nút Yêu thích (chỉ icon trái tim) -->
                            @auth
                              @php
                                $isFavorited = auth()->user()->favorites()->where('product_id', $product->id)->exists();
                              @endphp
                              <button class="btn btn-xs {{ $isFavorited ? 'btn-danger remove-favorite' : 'btn-outline-danger add-favorite' }}" 
                                      data-product-id="{{ $product->id }}"
                                      title="{{ $isFavorited ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                                <i class="fa {{ $isFavorited ? 'fa-heart' : 'fa-heart-o' }}"></i>
                              </button>
                            @else
                              <a href="{{ route('login') }}" class="btn btn-xs btn-outline-danger" title="Đăng nhập để yêu thích">
                                <i class="fa fa-heart-o"></i>
                              </a>
                            @endauth
                          </div>
                </div>
              </div>
            </div>
          @empty
                <div class="empty-products">
                  <div class="empty-icon">
                    <i class="fa fa-search"></i>
                  </div>
                  <h3 class="empty-title">Không tìm thấy sản phẩm</h3>
                  <p class="empty-description">Không có sản phẩm nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
                  <div class="empty-actions">
                        <a href="{{ route('client.product') }}" class="btn-explore">
                      <i class="fa fa-search"></i> Xem tất cả sản phẩm
                    </a>
              </div>
            </div>
          @endforelse
      </div>
      
      <!-- Pagination -->
      @if($products->hasPages())
        <div class="pagination-wrapper">
          {{ $products->appends(request()->query())->links() }}
        </div>
      @endif
    </div>
  </section>

  <!-- Modal chọn phiên bản -->
  <div class="modal fade" id="variantModal" tabindex="-1" role="dialog" aria-labelledby="variantModalLabel" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="variantModalLabel">Chọn phiên bản sản phẩm</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="variantModalContent">
            <!-- Nội dung sẽ được load bằng AJAX -->
            <div class="text-center">
              <i class="fa fa-spinner fa-spin fa-2x"></i>
              <p>Đang tải thông tin sản phẩm...</p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        </div>
      </div>
    </div>
  </div>

  <style>
  /* Fix modal backdrop issues */
  .modal-backdrop {
    z-index: 9998 !important;
    pointer-events: none !important;
    display: none !important;
  }
  
  .modal-backdrop.show {
    opacity: 0 !important;
    pointer-events: none !important;
    display: none !important;
  }
  
  #variantModal {
    z-index: 9999 !important;
  }
  
  .modal-dialog {
    max-width: 800px;
    z-index: 10000 !important;
  }
  
  .modal-content {
    position: relative;
    z-index: 10001 !important;
  }
  
  .modal-body {
    max-height: 70vh;
    overflow-y: auto;
  }
  
  /* Ensure modal is clickable */
  .modal.show {
    display: block !important;
  }
  
  /* Remove any conflicting overlays */
  .modal-backdrop.show {
    opacity: 0.5 !important;
  }
  
  /* Force modal to be interactive */
  .modal.show .modal-dialog {
    pointer-events: auto !important;
  }
  
  .modal.show .modal-content {
    pointer-events: auto !important;
  }
  </style>


  <script>
    // Price Range Slider functionality
    document.addEventListener('DOMContentLoaded', function() {
        const minSlider = document.getElementById('min-price-slider');
        const maxSlider = document.getElementById('max-price-slider');
        const minInput = document.getElementById('min-price-input');
        const maxInput = document.getElementById('max-price-input');
        const priceDisplay = document.getElementById('price-display');
        
        if (minSlider && maxSlider) {
            // Update display and hidden inputs when sliders change
            function updatePriceDisplay() {
                const minValue = parseInt(minSlider.value);
                const maxValue = parseInt(maxSlider.value);
                
                // Ensure min doesn't exceed max
                if (minValue > maxValue) {
                    if (minSlider === document.activeElement) {
                        maxSlider.value = minValue;
                    } else {
                        minSlider.value = maxValue;
                    }
                }
                
                const finalMin = Math.min(minSlider.value, maxSlider.value);
                const finalMax = Math.max(minSlider.value, maxSlider.value);
                
                minInput.value = finalMin;
                maxInput.value = finalMax;
                
                priceDisplay.textContent = `${new Intl.NumberFormat('vi-VN').format(finalMin)}đ - ${new Intl.NumberFormat('vi-VN').format(finalMax)}đ`;
            }
            
            minSlider.addEventListener('input', updatePriceDisplay);
            maxSlider.addEventListener('input', updatePriceDisplay);
            
            // Initialize display
            updatePriceDisplay();
            
            // Reset price range button
            const resetPriceBtn = document.getElementById('reset-price-range');
            if (resetPriceBtn) {
                resetPriceBtn.addEventListener('click', function() {
                    minSlider.value = 0;
                    maxSlider.value = 50000000;
                    updatePriceDisplay();
                });
            }
        }
    });
    
    // Basic functionality for add to cart and favorites
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof $ === 'undefined') {
            console.error('jQuery is not loaded!');
            return;
        }
        
        console.log('jQuery loaded successfully');
        console.log('Bootstrap modal available:', typeof $.fn.modal !== 'undefined');
        
        // Xử lý modal chọn phiên bản
        $(document).on('click', '.btn-select-variant', function(e) {
            e.preventDefault();
            console.log('Button clicked');
            
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            
            console.log('Product ID:', productId, 'Product Name:', productName);
            
            // Kiểm tra modal có tồn tại không
            if ($('#variantModal').length === 0) {
                console.error('Modal not found!');
                alert('Modal không tìm thấy!');
                return;
            }
            
            // Cập nhật tiêu đề modal
            $('#variantModalLabel').text('Chọn phiên bản: ' + productName);
            
            // Hiển thị loading
            $('#variantModalContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i><p>Đang tải thông tin sản phẩm...</p></div>');
            
            // Mở modal trước
            $('#variantModal').modal({
                backdrop: false,
                keyboard: true,
                show: true
            });
            
            // Load thông tin sản phẩm bằng AJAX
            $.ajax({
                url: '{{ route("client.get-product-variants") }}',
                method: 'GET',
                data: { product_id: productId },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('AJAX response:', response);
                    if (response.success) {
                        $('#variantModalContent').html(response.html);
                        console.log('HTML loaded successfully, length:', response.html.length);
                        
                        // Re-initialize any scripts in the loaded HTML
                        setTimeout(function() {
                            // Re-run any initialization scripts
                            if (typeof selectVariant === 'function') {
                                console.log('selectVariant function is available');
                            }
                            
                            // Disable variants that are out of stock
                            $('.variant-item').each(function() {
                                const stockText = $(this).find('.stock-info').text();
                                if (stockText.includes('Hết hàng')) {
                                    $(this).addClass('disabled');
                                }
                            });
                            
                            // Ensure modal is properly positioned and interactive
                            $('#variantModal').modal('handleUpdate');
                            
                            console.log('Modal content re-initialized');
                        }, 100);
                    } else {
                        $('#variantModalContent').html('<div class="alert alert-danger">' + response.message + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                    $('#variantModalContent').html('<div class="alert alert-danger">Có lỗi xảy ra khi tải thông tin sản phẩm. Vui lòng thử lại.</div>');
                }
            });
        });
        
        // Buy now functionality
        $(document).on('click', '.btn-buy-now', function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            
            console.log('Buy now clicked for product:', productId, productName);
            
            // Load product variants into modal
            $.ajax({
                url: '{{ route("client.get-product-variants") }}',
                method: 'GET',
                data: { product_id: productId },
                success: function(response) {
                    if (response.success) {
                        $('#variantModalContent').html(response.html);
                        $('#variantModal').modal({ backdrop: false, keyboard: true, show: true });
                        $('#variantModal').modal('handleUpdate');
                    } else {
                        alert(response.message || 'Có lỗi xảy ra khi tải thông tin sản phẩm!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    alert('Có lỗi xảy ra khi tải thông tin sản phẩm. Vui lòng thử lại.');
                }
            });
        });
        
        // Add to cart functionality
      $(document).on('submit', '.add-to-cart-form-quick', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $button = $form.find('.btn-add-cart');
            
            if ($button.hasClass('loading')) return;
            
        $button.addClass('loading').prop('disabled', true);
        const originalHtml = $button.html();
        $button.html('<i class="fa fa-spinner fa-spin"></i> Đang thêm...');
        
        $.ajax({
          url: $form.attr('action'),
          method: 'POST',
          data: $form.serialize(),
          success: function(response) {
            $button.removeClass('loading').prop('disabled', false);
            if (response.success) {
              $button.html('<i class="fa fa-check"></i> Đã thêm!');
                        setTimeout(() => $button.html(originalHtml), 2000);
            } else {
              $button.html(originalHtml);
                alert(response.message);
            }
          },
                error: function() {
            $button.removeClass('loading').prop('disabled', false);
            $button.html(originalHtml);
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                }
      });
    });
    
        // Favorite functionality
      $(document).on('click', '.add-favorite, .remove-favorite', function(e) {
        e.preventDefault();
        const $button = $(this);
        const productId = $button.data('product-id');
        
            if ($button.hasClass('loading')) return;
            
        $button.addClass('loading').prop('disabled', true);
        const originalHtml = $button.html();
        $button.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
        
        const isCurrentlyFavorited = $button.hasClass('remove-favorite');
        const url = isCurrentlyFavorited ? '{{ route("client.favorite.remove") }}' : '{{ route("client.favorite.add") }}';
        
        $.ajax({
          url: url,
          method: 'POST',
          data: {
            product_id: productId,
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            $button.removeClass('loading').prop('disabled', false);
            if (response.success) {
                        if (isCurrentlyFavorited) {
                $button.removeClass('remove-favorite btn-danger').addClass('add-favorite btn-outline-danger');  
                $button.html('<i class="fa fa-heart-o"></i> Yêu thích');
                  } else {
                            $button.removeClass('add-favorite btn-outline-danger').addClass('remove-favorite btn-danger');
                            $button.html('<i class="fa fa-heart"></i> Bỏ yêu thích');
                  }
              
              if (response.favorite_count !== undefined) {
                            $(`.product-${productId}-favorites`).text(response.favorite_count);
              }
            } else {
              $button.html(originalHtml);
                alert(response.message);
            }
          },
                error: function() {
            $button.removeClass('loading').prop('disabled', false);
            $button.html(originalHtml);
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
          }
        });
      });
    });
  </script>
@endsection