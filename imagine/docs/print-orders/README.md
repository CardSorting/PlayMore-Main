# Print Orders Module

This module handles the creation, management, and fulfillment of print orders in the Imagine platform.

## Architecture

The print orders module follows a domain-driven design approach with clear separation of concerns:

### Core Components

- **Models**: `PrintOrder` represents the main entity
- **DTOs**: Data transfer objects for encapsulating request data
- **Actions**: Single-responsibility classes for business logic
- **Events**: Domain events for tracking order lifecycle
- **Listeners**: Event handlers for side effects
- **Services**: Core business services
- **View Models**: Presentation logic
- **Policies**: Authorization rules

### Directory Structure

```
app/
├── Actions/Print/
│   ├── CreatePrintOrderAction.php
│   └── ProcessPaymentAction.php
├── DTOs/
│   ├── PrintOrderData.php
│   └── PaymentData.php
├── Events/
│   ├── PrintOrderCreated.php
│   ├── PrintOrderStatusChanged.php
│   └── PrintOrderRefunded.php
├── Exceptions/
│   ├── PrintOrderException.php
│   └── PaymentException.php
├── Http/
│   ├── Controllers/
│   │   ├── PrintOrderController.php
│   │   └── Admin/PrintOrderController.php
│   ├── Middleware/
│   │   └── PrintOrderAccess.php
│   └── Requests/
│       └── Print/
│           ├── CreatePrintOrderRequest.php
│           └── ProcessPaymentRequest.php
├── Jobs/
│   └── ExportPrintOrders.php
├── Listeners/
│   └── PrintOrder/
│       ├── HandleRefund.php
│       ├── InitiatePrintProduction.php
│       └── PrintOrderEventSubscriber.php
├── Models/
│   └── PrintOrder.php
├── Notifications/
│   ├── PrintOrderExportCompleted.php
│   ├── PrintOrderRefunded.php
│   └── PrintOrderRefundFailed.php
├── Policies/
│   └── PrintOrderPolicy.php
├── Services/
│   └── PrintOrderService.php
└── ViewModels/
    └── PrintOrderViewModel.php
```

## Features

- Order creation from gallery images
- Multiple print sizes with dynamic pricing
- International shipping support
- Payment processing
- Order status management
- Refund processing
- Batch operations for admins
- Export functionality
- Comprehensive logging and metrics

## Order Lifecycle

1. **Creation**
   - User selects image from gallery
   - Chooses print size
   - Enters shipping details
   - Order created in 'pending' status

2. **Payment**
   - Payment processed
   - Order moves to 'processing' status
   - Customer notified

3. **Production**
   - Print job queued
   - Quality checks performed
   - Production status tracked

4. **Shipping**
   - Tracking number assigned
   - Status updated to 'shipped'
   - Customer notified

5. **Completion**
   - Delivery confirmed
   - Status updated to 'completed'

## Order Statuses

- `pending`: Created but not paid
- `processing`: Paid and in production
- `shipped`: Sent to customer
- `completed`: Delivered successfully
- `cancelled`: Cancelled by customer or admin
- `refunded`: Full refund processed

## Events

The module uses events to handle side effects and maintain loose coupling:

- `PrintOrderCreated`: New order created
- `PrintOrderStatusChanged`: Status updates
- `PrintOrderRefunded`: Refund processed

## Notifications

Customers and admins receive notifications for:

- Order confirmation
- Payment confirmation
- Shipping updates
- Refund processing
- Export completion

## Admin Features

Administrators can:

- View all orders
- Update order status
- Process refunds
- Add tracking information
- Export order data
- View metrics and analytics

## Configuration

Key configuration files:

- `config/prints.php`: Print-specific settings
- `config/location.php`: Shipping zones and rates
- `config/logging.php`: Log channel configuration

## Logging

Dedicated log channels for:

- Order operations (`orders`)
- Error tracking (`orders-error`)
- Metrics (`metrics`)
- Exports (`exports`)
- Refunds (`refunds`)
- Production (`production`)
- Shipping (`shipping`)

## Testing

Run the test suite:

```bash
php artisan test --filter=PrintOrder
```

Key test files:

```
tests/
├── Feature/
│   └── PrintOrder/
│       ├── CreatePrintOrderTest.php
│       ├── ProcessPaymentTest.php
│       └── RefundTest.php
└── Unit/
    └── PrintOrder/
        ├── PrintOrderServiceTest.php
        └── PrintOrderValidationTest.php
```

## API Documentation

### Customer Endpoints

```
GET    /dashboard/prints                    # List user's orders
POST   /dashboard/prints/gallery/{id}       # Create order
GET    /dashboard/prints/{order}            # View order
POST   /dashboard/prints/{order}/payment    # Process payment
```

### Admin Endpoints

```
GET    /admin/prints                        # List all orders
GET    /admin/prints/{order}                # View order details
POST   /admin/prints/{order}/status         # Update status
POST   /admin/prints/{order}/refund         # Process refund
POST   /admin/prints/batch/export           # Export orders
```

## Contributing

1. Follow the established architecture
2. Add appropriate tests
3. Update documentation
4. Submit a pull request

## Security

- All routes protected by authentication
- Role-based access control
- Validation on all input
- Secure payment processing
- Activity logging
- Error tracking

## Metrics

The module tracks:

- Order volume
- Processing times
- Shipping performance
- Refund rates
- Customer satisfaction
- Revenue metrics

## Dependencies

- Laravel Framework
- Stripe for payments
- AWS S3 for storage
- Redis for queues
- Papertrail for logging
