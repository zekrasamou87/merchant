<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationRequestResource\Pages;
use App\Models\ApplicationRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApplicationRequestResource extends Resource
{
    protected static ?string $model = ApplicationRequest::class;

    // أيقونة الطلبات وترتيبها
    protected static ?string $navigationIcon = 'heroicon-o-document-plus';
    protected static ?string $navigationLabel = 'طلبات الانضمام';
    protected static ?string $modelLabel = 'طلب انضمام';
    protected static ?string $pluralModelLabel = 'طلبات الانضمام';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('store_id')
                            ->label('المحل التجاري')
                            ->relationship('store', 'store_name') // ربط الطلب بالمحل
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('incoming_number')
                            ->label('رقم الوارد (الرسمي)')
                            ->required(),

                        Forms\Components\DatePicker::make('application_date')
                            ->label('تاريخ تقديم الطلب')
                            ->default(now())
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('حالة الطلب')
                            ->options([
                                'pending' => 'قيد الانتظار',
                                'accepted' => 'تم القبول ✅',
                                'rejected' => 'تم الرفض ❌',
                            ])
                            ->default('pending')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('incoming_number')
                    ->label('رقم الوارد')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('store.store_name')
                    ->label('اسم المحل')
                    ->searchable(),

                Tables\Columns\TextColumn::make('application_date')
                    ->label('التاريخ')
                    ->date()
                    ->sortable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'accepted' => 'مقبول',
                        'rejected' => 'مرفوض',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('تصفية حسب الحالة')
                    ->options([
                        'pending' => 'قيد الانتظار',
                        'accepted' => 'مقبول',
                        'rejected' => 'مرفوض',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplicationRequests::route('/'),
            'create' => Pages\CreateApplicationRequest::route('/create'),
            'edit' => Pages\EditApplicationRequest::route('/{record}/edit'),
        ];
    }
}