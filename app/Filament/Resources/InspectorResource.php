<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InspectorResource\Pages;
use App\Models\Inspector;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InspectorResource extends Resource
{
    protected static ?string $model = Inspector::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'المراقبين';
    protected static ?string $modelLabel = 'مراقب';
    protected static ?string $pluralModelLabel = 'المراقبين';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('full_name')
                            ->label('الاسم الكامل')
                            ->required(),
                        
                        Forms\Components\TextInput::make('employee_id')
                            ->label('الرقم الوظيفي')
                            ->unique(ignoreRecord: true)
                            ->required(),

                        Forms\Components\TextInput::make('phone_number')
                            ->label('رقم الهاتف')
                            ->tel(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('على رأس عمله')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('employee_id')
                    ->label('الرقم الوظيفي')
                    ->copyable(),

                Tables\Columns\TextColumn::make('phone_number')
                    ->label('الهاتف'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),

                Tables\Columns\TextColumn::make('inspections_count')
                    ->label('عدد الزيارات')
                    ->counts('inspections'), // بيحسب تلقائياً كم زيارة عمل هاد المراقب
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('تصفية حسب الحالة'),
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
            'index' => Pages\ListInspectors::route('/'),
            'create' => Pages\CreateInspector::route('/create'),
            'edit' => Pages\EditInspector::route('/{record}/edit'),
        ];
    }
}